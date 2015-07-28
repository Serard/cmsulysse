<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Command;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/command")
 */
class CommandController extends Controller
{
    /**
     * @Route("user/list", name="commands_user")
     * @Template("CmsUlysseBundle:Command:list.html.twig")
     */
    public function listUserAction()
    {
        $id = $this->container->get('security.context')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $commands = $em->getRepository('CmsUlysseBundle:Command')->findUserCommands($id);
        $states   = $em->getRepository('CmsUlysseBundle:State')->findAll();

        $users   = array();
        $numbers = array();
        foreach ($commands as $command) {
            foreach ($command->getProducts()->getValues() as $product) {
                $users   [] = $product->getProduct()->getUser()->getFirstName().' '.$product->getProduct()->getUser()->getLastName();
                $numbers [] = $product->getProduct()->getUser()->getTel();
                break;
            }
        }

        return array(
            'title'    => 'Mes Achats',
            'states'   => $states,
            'commands' => $commands,
            'users'    => $users,
            'numbers'  => $numbers,
        );
    }

    /**
     * @Route("user/buy", name="command_buy")
     */
    public function buyAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $command = $em->getRepository('CmsUlysseBundle:Command')->find($id);
        if ($command) {
            $state   = $em->getRepository('CmsUlysseBundle:State')->find(2);
            $command->setSendAt(new \DateTime())
                    ->setState($state);

            $em->persist($command);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('commands_user'));
    }

    /**
     * @Route("user/received", name="command_received")
     */
    public function receivedAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $command = $em->getRepository('CmsUlysseBundle:Command')->find($id);
        if ($command) {
            $state   = $em->getRepository('CmsUlysseBundle:State')->find(4);
            $command->setSendAt(new \DateTime())
                    ->setState($state);

            $em->persist($command);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('commands_user'));
    }

    /**
     * @Route("seller/list", name="commands_seller")
     * @Template("CmsUlysseBundle:Command:list.html.twig")
     */
    public function listSellerAction()
    {
        $id = $this->container->get('security.context')->getToken()->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $commands = $em->getRepository('CmsUlysseBundle:Command')->findSellerCommands($id);
        $states   = $em->getRepository('CmsUlysseBundle:State')->findAll();

        $users   = array();
        $numbers = array();
        foreach ($commands as $command) {
            $users   [] = $command->getUser()->getFirstName().' '.$command->getUser()->getLastName();
            $numbers [] = $command->getUser()->getTel();
        }

        return array(
            'title'    => 'Mes Ventes',
            'states'   => $states,
            'commands' => $commands,
            'users'    => $users,
            'numbers'  => $numbers,
        );
    }

    /**
     * @Route("seller/send", name="command_send")
     */
    public function sendAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();

        $command = $em->getRepository('CmsUlysseBundle:Command')->find($id);
        if ($command) {
            $state   = $em->getRepository('CmsUlysseBundle:State')->find(3);
            $command->setSendAt(new \DateTime())
                    ->setState($state);

            $em->persist($command);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('commands_seller'));
    }

    /**
     * @Route("user/{id}", name="command_user_view")
     * @Template("CmsUlysseBundle:Command:view.html.twig")
     */
    public function viewCommandUserAction(Command $command)
    {
        return array(
            'title'   => 'Mon Achat',
            'command' => $command,
        );
    }

    /**
     * @Route("seller/{id}", name="command_seller_view")
     * @Template("CmsUlysseBundle:Command:view.html.twig")
     */
    public function viewCommandSellerAction(Command $command)
    {
        return array(
            'title'   => 'Ma Vente',
            'command' => $command,
        );
    }
}
