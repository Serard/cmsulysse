<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

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
        $em = $this->getDoctrine()->getManager();

        $sess = $this->get('session');
        $sess->remove('category');

        $products = $em->getRepository('CmsUlysseBundle:UserProduct')
                       ->findAll();

        $categories  = $em->getRepository('CmsUlysseBundle:Category')->findCategsUp();
        $categs_down = $em->getRepository('CmsUlysseBundle:Category')->findCategsDown();

        return array(
                'products'    => $products,
                'category'    => null,
                'categories'  => $categories,
                'categs_down' => $categs_down,
            );
    }

    /**
     * @Route("/category/{id}", name="categ_cart")
     * @Template()
     */
    public function categAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sess = $this->get('session');
        $sess->set('category', $request->get('id'));

        $entity = $em->getRepository('CmsUlysseBundle:Category')
                     ->find($request->get('id'));
        if ($entity) {
            $name = $entity->getName();
            $categories = $em->getRepository('CmsUlysseBundle:Category')
                             ->findDownLevel($request->get('id'));
        }

        $products = array();
        if ($categories) {
            $save = array();
            foreach($categories as $entity) {
                $dbproducts = $em->getRepository('CmsUlysseBundle:UserProduct')
                                 ->findSearchProducts(null, $entity->getId());
                foreach ($dbproducts as $product) {
                    $products[$product->getId()] = $product;
                }
            }
            $products = array_values($products);
        } else {
            $dbproducts = $em->getRepository('CmsUlysseBundle:UserProduct')
                ->findSearchProducts(null, $request->get('id'));
            foreach ($dbproducts as $product) {
                $products[] = $product;
            }
        }

        $categories  = $em->getRepository('CmsUlysseBundle:Category')->findCategsUp();
        $categs_down = $em->getRepository('CmsUlysseBundle:Category')->findCategsDown();

        return array(
            'products'    => $products,
            'category'    => $name,
            'categories'  => $categories,
            'categs_down' => $categs_down,
        );
    }

    /**
     * @Route("/search", name="search_cart")
     * @Template("CmsUlysseBundle:Cart:search.html.twig")
     */
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $sess       = $this->get('session');
        $category   = $sess->get('category');
        $name       = null;
        $categories = null;
        if (!empty($category)) {
            $entity = $em->getRepository('CmsUlysseBundle:Category')
                         ->find($category);
            $name = $entity->getName();
            $categories = $em->getRepository('CmsUlysseBundle:Category')
                             ->findDownLevel($category);
        }

        $products = array();
        if ($categories) {
            foreach($categories as $entity) {
                $dbproducts = $em->getRepository('CmsUlysseBundle:UserProduct')
                                 ->findSearchProducts($request->get('search'), $entity->getId());
                ;
                foreach ($dbproducts as $product) {
                    $products[$product->getId()] = $product;
                }
            }
            $products = array_values($products);
        } else {
            $dbproducts = $em->getRepository('CmsUlysseBundle:UserProduct')
                             ->findSearchProducts($request->get('search'), $category);
            foreach ($dbproducts as $product) {
                $products[] = $product;
            }
        }

        $categories  = $em->getRepository('CmsUlysseBundle:Category')->findCategsUp();
        $categs_down = $em->getRepository('CmsUlysseBundle:Category')->findCategsDown();


        return array(
            'products'    => $products,
            'category'    => $name,
            'categories'  => $categories,
            'categs_down' => $categs_down,
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