<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="index_admin")
     * @Template()
     */
    public function indexAction(){
        $repo = $this->getDoctrine()->getManager()->getRepository('CmsUlysseBundle:Category');
        $categories = $repo->findAll();
        $products = $this->getReposProduct()->findByValid(0);

        return array(
            'products' => $products,
            'categories' => $categories,
            'context' => 'new_products'
        );
    }

    /**
     * @Route("/products", name="product_admin")
     * @Template("CmsUlysseBundle:Admin:index.html.twig")
     */
    public function productAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('CmsUlysseBundle:Category');
        $categories = $repo->findAll();
        $products = $this->getReposProduct()->findAll();

        return array(
            'products' => $products,
            'categories' => $categories,
            'context' => 'list_product'
        );
    }

    /**
     * @Route("/product/{id}", name="valid_product_admin")
     * @Template()
     */
    public function validProductAction($id)
    {
        $product = $this->getReposProduct()->find($id);

        $product->setValid(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('index_admin');
    }


    public function getReposProduct()
    {
        $em = $this->getDoctrine()->getManager();
        return ($em->getRepository('CmsUlysseBundle:Product'));
    }

}