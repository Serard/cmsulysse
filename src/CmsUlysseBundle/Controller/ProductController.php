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
     * @Template("CmsUlysseBundle:Product:form.html.twig")
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

        $specifications = $em->getRepository('CmsUlysseBundle:Specification')->findByProduct($product);
        foreach($specifications as $specification){
            $em->remove($specification);
        }
        $em->remove($product);
        $em->flush();

        return $this->redirect($this->generateUrl("product_list"));

    }

    /**
     * @Route("/update/{id}", name="product_update")
     * @Template("CmsUlysseBundle:Product:form.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Product');
        $product = $repo->find($request->get('id'));
        $specifications = $em->getRepository('CmsUlysseBundle:Specification')->findByProduct($product);

        $form = $this->createForm(new ProductType(),$product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirect($this->generateUrl('product_list'));
        }
        return array(
            'form' => $form->createView(),
            'specifications' => $specifications
        );
    }
    /**
     * @Route("/search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        //$research = $_POST['suguest'];

        //retrun $id;
    }


}
