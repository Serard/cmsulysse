<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Slider;
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
     * @Route("/modules", name="modules_admin")
     * @Template("CmsUlysseBundle:Admin:Module/index.html.twig")
     */
    public function modulesAction()
    {

        return array();
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
