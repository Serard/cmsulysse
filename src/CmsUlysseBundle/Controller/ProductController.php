<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Product;
use CmsUlysseBundle\Entity\Specification;
use CmsUlysseBundle\Form\Type\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/", name="product_list")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Product');
        $products = $repository->findAll();

        return array('products' => $products);
    }

    /**
     * @Route("/add", name="product_add")
     * @Template()
     *
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new ProductType(), new Product());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            foreach($product->getSpecifications() as $specification){
                $specification->setProduct($product);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('product_list'));
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/{id}", name="product_view")
     * @Template()
     */
    public function viewAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $repo = $manager->getRepository('CmsUlysseBundle:Product');
        $product = $repo->find($id);

        return array(
            'product' => $product
        );
    }

    /**
     * @Route("/delete/{id}", name="product_delete")
     * @Template()
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Product');
        $product= $repo->find($id);
        $em->remove($product);
        $em->flush();

        return $this->redirect($this->generateUrl("product_list"));

    }

    /**
     * @Route("/update/{id}")
     * @Template()
     */
    public function updateAction($id)
    {
        return array();
    }

}
