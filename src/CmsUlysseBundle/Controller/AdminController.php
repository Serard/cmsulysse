<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Product;
use CmsUlysseBundle\Entity\Site;
use CmsUlysseBundle\Entity\Slider;
use CmsUlysseBundle\Form\Type\AdminProductType;
use CmsUlysseBundle\Form\Type\SliderType;
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
     * @Route("/product/add", name="product_add_admin")
     * @Template("CmsUlysseBundle:Admin:product/form.html.twig")
     *
     */
    public function productAddAction(Request $request)
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
            foreach($product->getPictures() as $picture){
                $picture->setProduct($product);
            }
            foreach($product->getSpecifications() as $specification){
                $specification->setProduct($product);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirect($this->generateUrl('product_admin'));
        }
        return array('form' => $form->createView(), 'onlyProduct' => true);
    }

    /**
     * @Route("/product/{id}", name="product_view_admin")
     * @Template("CmsUlysseBundle:Admin:product/view.html.twig")
     */
    public function productViewAction(Product $product)
    {
          return array('product' => $product);
    }

    /**
     * @Route("/modules", name="modules_admin")
     * @Template("CmsUlysseBundle:Admin:Module/index.html.twig")
     */
    public function modulesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Site');
        $site = $repository->findOneBy(array());

        return array('site' => $site);
    }

    /**
     * @Route("/slider", name="slider_admin")
     * @Template("CmsUlysseBundle:Admin:Module/slide.html.twig")
     */
    public function sliderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Slider');
        $slider = $repo->findOneBy(array(), null, 1);

        $form = $this->createForm(new SliderType(), $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slider = $form->getData();

            foreach($slider->getPictures() as $picture){
                $picture->setSlider($slider);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();
        }

        return array(
            'pictures' => $slider->getPictures(),
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/product/valid/{id}", name="valid_product_admin")
     * @Template()
     */
    public function validProductAction(Product $product)
    {
        $product->setValid(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('index_admin');
    }

    public function getReposProduct()
    {
        $em = $this->getDoctrine()->getManager();
        return ($em->getRepository('CmsUlysseBundle:Product'));
    }

    /**
     * @Route("/config/slide/{id}/edit", name="active_slider_admin")
     * @Template()
     */
    public function activeSliderAction(Site $site)
    {
        $site->setSlider(!$site->getSlider());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('modules_admin');
    }

    /**
     * @Route("/config/bestProduct/{id}/edit", name="active_best_product_admin")
     * @Template()
     */
    public function activeBestProductAction(Site $site)
    {
        $site->setBestProduct(!$site->getBestProduct());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('modules_admin');
    }




}
