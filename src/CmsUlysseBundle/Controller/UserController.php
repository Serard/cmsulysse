<?php

namespace CmsUlysseBundle\Controller;

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
            return $this->redirect($this->generateUrl("user_update"));
        }

        $userType = new UserType();
        $form = $this->createForm($userType, $user);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $sess = $this->get('session');
            $fb = $sess->getFlashbag();

            $mail = $repo->findBy(
                array('email' => $_POST['user_form']['email'])
            );

            $pseudo = $repo->findBy(
                array('username' => $_POST['user_form']['username'])
            );

            $valid = true;
            if ($mail){
                if ($mail[0]->getId() != $id) {
                    $fb->add('alert', "Cet email existe déja");
                    $valid = false;
                }
            }

            if ($pseudo) {
                if ($pseudo[0]->getId() != $id) {
                    $fb->add('alert', "Ce pseudo existe déjà");
                    $valid = false;
                }
            }

            if ($valid) {
                $em->persist($form->getData());
                $em->flush();
                $fb->add('succes', "Modification effectuée");
            }

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

            );    }

}
