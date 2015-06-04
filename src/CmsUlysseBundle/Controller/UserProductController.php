<?php
namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\UserProduct;
use CmsUlysseBundle\Form\Type\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UserProductController extends Controller
{
    /**
     * @Route("/user/product/", name="user_product_list")
     * @Template()
     */
    public function indexUsuerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:UserProduct');
        $UserProducts = $repository->findByUser($this->container->get('security.context')->getToken()->getUser());

        return array('products' => $UserProducts);
    }
}