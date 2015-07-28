<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("")
 */
class DefaultController extends Controller
{
    /**
     * @Route("", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Slider');
        $slider = $repo->findOneBy(array(), null, 1);

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Product');

        $products = array();
        $newProducts = array();

        $categories = $em->getRepository('CmsUlysseBundle:Category')->findCategsUp();

        foreach ($categories as $category) {
            $productsCount=$repository->findProductsByCategoryUp($category,3);
            if(count($productsCount)) $products[$category->getName()] = $productsCount;
        }


        foreach ($categories as $category) {
            $productsNewCount=$repository->findNewProductsByCategoryUp($category,3);
            if(count($productsNewCount)) $newProducts[$category->getName()] = $productsNewCount;
        }

            return array(
                'products' => $products,
                'newProduct' => $newProducts,
                'slides' => $slider->getPictures()

            );
    }
}
