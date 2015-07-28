<?php
namespace CmsUlysseBundle\Services\OAuth;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider extends BaseClass
{

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {

        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter       = 'set' . ucfirst($service);
        $setter_id    = $setter . 'Id';
        $setter_token = $setter . 'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {

        $paths = $response->getPaths();
        $property     = $this->getProperty($response);
        $responseData = $response->getResponse();

        # var_dump($responseData);
        # die;

        if($property == 'doyobuzzId'){
            $responseData = $responseData['user'];
        }

        $username     = $responseData[$paths['identifier']];

        $data = array(
            'username'   => isset($responseData[$paths['email']])? $responseData[$paths['email']] : $username,
            'first_name' => isset($responseData[$paths['nickname']])? $responseData[$paths['nickname']] : $username,
            'last_name'  => isset($responseData[$paths['realname']])? $responseData[$paths['realname']] : $username,
            'email'      => isset($responseData[$paths['email']])? $responseData[$paths['email']] : $username,
        );


        switch ($property) {

            case 'googleId':

                if (isset($responseData['email'])) {
                    $data['username'] = $responseData['email'];
                }

                if (isset($responseData['given_name'])) {
                    $data['last_name'] = $responseData['given_name'];
                }

                if (isset($responseData['family_name'])) {
                    $data['first_name'] = $responseData['family_name'];
                }

                if (isset($responseData['email'])) {
                    $data['email'] = $responseData['email'];
                }

                break;

            case 'facebookId':

                if (isset($responseData['email'])) {
                    $data['username'] = $responseData['email'];
                }

                if (isset($responseData['last_name'])) {
                    $data['last_name'] = $responseData['last_name'];
                }

                if (isset($responseData['first_name'])) {
                    $data['first_name'] = $responseData['first_name'];
                }

                if (isset($responseData['email'])) {
                    $data['email'] = $responseData['email'];
                }

                break;

        }

        $user      = $this->userManager->findUserBy(array($property => $username));
        $userEmail = $this->userManager->findUserBy(array('username' => $data['username']));

        if (null != $userEmail && null === $user) {

            $service      = $response->getResourceOwner()->getName();
            $setter       = 'set' . ucfirst($service);
            $setter_id    = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';
            $userEmail->$setter_id($username);
            $userEmail->$setter_token($response->getAccessToken());
            $userEmail->setFBGOData($data);
            $userEmail->setEnabled(true);
            $this->userManager->updateUser($userEmail);

            return $userEmail;

        } elseif (null === $user) {
            $service      = $response->getResourceOwner()->getName();
            $setter       = 'set' . ucfirst($service);
            $setter_id    = $setter . 'Id';
            $setter_token = $setter . 'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setFBGOData($data);
            $user->setPassword(sha1($username));
            $user->setEnabled(true);
            $this->userManager->updateUser($user);

            return $user;

        }

        $serviceName = $response->getResourceOwner()->getName();
        $setter      = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }

}