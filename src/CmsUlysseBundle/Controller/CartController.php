<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
/**
 * @Route("/market")
 */
class CartController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function listAction()
    {
        $products = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('CmsUlysseBundle:UserProduct')
                         ->findMarketProducts();

        return array(
                'products' => $products
            );    }

    /**
     * @Route("/update")
     * @Template()
     */
    public function updateAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/delete")
     * @Template()
     */
    public function deleteAction()
    {
        return array(
                // ...
            );    }

    /**
     * @Route("/cart")
     * @Template()
     */
    public function cartAction()
    {
        return array(
                // ...
            );    }

}
