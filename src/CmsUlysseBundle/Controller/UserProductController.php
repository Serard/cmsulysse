<?php
namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\UserProduct;
use CmsUlysseBundle\Form\Type\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    /**
     * @Route("/user/product/", name="user_product_list")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:UserProduct');
        $UserProducts = $repository->findByUser($this->container->get('security.context')->getToken()->getUser());

        return array('products' => $UserProducts);
    }

    /**
     * @Route("/user/product/add", name="user_product_add")
     * @Template()
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new ProductType(), new UserProduct());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userProduct = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($userProduct);
            $em->flush();

            return $this->redirect($this->generateUrl('user_product_list'));
        }

        return array('form' => $form->createView());
    }