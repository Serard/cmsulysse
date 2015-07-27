<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Product;
use CmsUlysseBundle\Entity\Specification;
use CmsUlysseBundle\Form\Type\ProductType;
use CmsUlysseBundle\Form\Type\AdminProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class ProductController extends Controller
{
    /**
     * @Route("/product", name="product_list")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Product');
        $products = $repository->findValidate();

        return array('products' => $products);
    }

    /**
     * @Route("/product/add", name="product_add")
     * @Template("CmsUlysseBundle:Product:form.html.twig")
     *
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new ProductType(), new Product());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.context')->getToken()->getUser();
            $product = $form->getData();

            foreach($product->getSpecifications()->getValues() as $specification) {
                if ($specification->getName() === null && $specification->getContent() === null) {
                    $product->removeSpecification($specification);
                }
            }

            foreach($product->getSpecifications() as $specification){
                $specification->setProduct($product);
            }

            foreach($product->getUserProducts() as $userProduct){
                $userProduct->setProduct($product);
                $userProduct->setUser($user);
            }
            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('product_list'));
        }
        return array('form' => $form->createView());
    }

    /**
     * @Route("/admin/product/validate", name="product_validate")
     * @Template("CmsUlysseBundle:Product:validate.html.twig")
     *
     */
    public function validateAction(Request $request)
    {
        $em   = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Product');
        if ($request->getMethod() === 'POST') {
            $product = $repo->find($request->get('id'));
            if ($product) {
                $product->setValid(true);
                $em->persist($product);
                $em->flush();
            }
        }

        $products = $repo->findNoValidate();

        return array('products' => $products);
    }

    /**
     * @Route("/admin/product/add", name="product_admin_add")
     * @Template("CmsUlysseBundle:Product:form.html.twig")
     *
     */
    public function adminAddAction(Request $request)
    {
        $form = $this->createForm(new AdminProductType(), new Product());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            foreach($product->getSpecifications()->getValues() as $specification) {
                if ($specification->getName() === null && $specification->getContent() === null) {
                    $product->removeSpecification($specification);
                }
            }

            foreach($product->getSpecifications() as $specification){
                $specification->setProduct($product);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('product_list'));
        }
        return array('form' => $form->createView(), 'onlyProduct' => true);
    }

    /**
     * @Route("/product/{id}", name="product_view")
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
     * @Route("/product/delete/{id}", name="product_delete")
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
     * @Route("/product/update/{id}", name="product_update")
     * @Template("CmsUlysseBundle:Product:form.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Product');
        $product = $repo->find($request->get('id'));
        $specifications = $em->getRepository('CmsUlysseBundle:Specification')->findByProduct($product);


        $form = $this->createForm(new AdminProductType(),$product);
        $usersProduct = $product->getUserProducts();
        $disabled = false;
        foreach($usersProduct as $usersProduct) {
            $disabled = true;
            break;
        }

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') || $disabled) {
            $form->add('valid', null, array('label' => 'Validé : ', 'required' => false, 'disabled' => true));
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            foreach($product->getSpecifications()->getValues() as $specification) {
                if ($specification->getName() === null && $specification->getContent() === null) {
                    $product->removeSpecification($specification);
                    $em->remove($specification);
                }
            }

            foreach($product->getSpecifications() as $specification){
                $specification->setProduct($product);
            }

            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('product_list'));
        }
        return array(
            'form' => $form->createView(),
            'specifications' => $specifications,
            'onlyProduct' => true
        );
    }

    /**
     * @Route("/product/search")
     * @Template()
     */
    public function searchAction(Request $request)
    {
        //$research = $_POST['suguest'];

        //retrun $id;
    }


}
