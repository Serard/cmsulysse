<?php
namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class HeaderController extends Controller
{


    public function leftmAction()
    {

        $em = $this->getDoctrine()->getManager();

        $categories  = $em->getRepository('CmsUlysseBundle:Category')->findCategsUp();
        $categs_down = $em->getRepository('CmsUlysseBundle:Category')->findCategsDown();

        return $this->render('CmsUlysseBundle:Main:menuLeft.html.twig',  array(
                'categories'  => $categories,
                'categs_down' => $categs_down,
            )
        );


    }

    public function rightAction()
    {

    }
}
