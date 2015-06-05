<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/market")
 */
class CartController extends Controller
{
    /**
     * @Route("/", name="market_cart")
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
            );
    }

    /**
     * @Route("/update", name="update_cart")
     * @Template()
     */
    public function updateAction()
    {
        return array(
                // ...
            );
    }

    /**
     * @Route("/delete", name="delete_cart")
     * @Template()
     */
    public function deleteAction()
    {
        return array(
                // ...
            );
    }

    /**
     * @Route("/cart", name="user_cart")
     * @Template()
     */
    public function cartAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('CmsUlysseBundle:UserProduct');
        $products = json_decode($request->cookies->get('cart'));

        if ($products) {
            foreach ($products as $product) {
                $dbproduct = $repo->find($product->id);
                $product->name        = $dbproduct->getProduct()->getName();
                $product->description = $dbproduct->getProduct()->getDescription();
                $product->price       = $dbproduct->getPrice();
                $product->stock       = $dbproduct->getQty();
                $product->seller      = $dbproduct->getUser()->getFirstname().' '.$dbproduct->getUser()->getLastName();
            }
        }

        return array(
                'products' => $products
            );
    }

}
