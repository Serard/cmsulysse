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

    /**
     * @Route("/contact-admin", name="contact_admin")
     * @Template("CmsUlysseBundle:User:contactAdmin.html.twig")
     */
    public function contactAdminAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('objet', 'text', array('required' => true))
            ->add('message', 'textarea', array('required' => true))
            ->add('envoyer', 'submit', array(
                'attr' => array('class' => 'btn btn-primary'),
                'label' => false))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message = $form->getData();
            $user = $this->get('security.context')->getToken()->getUser();
            $message['sender'] = $user;

            $mail = \Swift_Message::newInstance()
                ->setSubject($message['message'])
                ->setFrom($user->getEmail())
                ->setTo('pauline.rdc@gmail.com')
                ->setBody($this->renderView(
                    'CmsUlysseBundle:Mailing:contactAdmin.txt.twig',
                    array('message' => $message))
                );

            $this->get('mailer')->send($mail);

            return($this->redirect($this->generateUrl('home')));
        }
        return array(
            'form' => $form->createView()
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
