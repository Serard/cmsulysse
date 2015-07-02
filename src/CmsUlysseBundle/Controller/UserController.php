<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\User;
use CmsUlysseBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/user/update", name="user_update")
     * @Template()
     */
    public function updateAction(Request $request)
    {
        $id = $this->container->get('security.context')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:User');

        $user = $repo->find($id);

        if (!$user) {
            return $this->redirect($this->generateUrl("user_homepage"));
        }

        $userType = new UserType();
        $form = $this->createForm($userType, $user);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $sess = $this->get('session');
            $fb = $sess->getFlashbag();
            $em->persist($form->getData());
            $em->flush();
            $fb->add('succes', "Modification effectuée");

            return $this->redirect($this->generateUrl("user_update"));
        }

        return array(
                'form' => $form->createView()
            );    }

    /**
     * @Route("/", name="user_homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array(

            );
    }

    public function contactAction(User $user, $subject, $body)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:User');
        $usersAdmin = $repo->findUsersByRole();

        foreach($usersAdmin as $admin){
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($user->getEmail())
                ->setTo($admin->getEmail())
                ->setBody($body);

            $this->get('mailer')->send($message);
        }

        return $this;
    }
}
