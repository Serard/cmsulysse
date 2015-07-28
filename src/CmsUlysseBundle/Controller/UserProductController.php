<?php
namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\UserProduct;
use CmsUlysseBundle\Form\Type\ProductType;
use CmsUlysseBundle\Form\Type\AdminProductType;
use CmsUlysseBundle\Form\Type\UserProductType;
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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:UserProduct');
        $UserProducts = $repository->findByUser($this->container->get('security.context')->getToken()->getUser());

        return array('products' => $UserProducts);
    }

    /**
     * @Route("/user/product/update/{id}", name="product_user_update")
     * @Template("CmsUlysseBundle:UserProduct:form.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:UserProduct');
        $repository = $em->getRepository('CmsUlysseBundle:Product');
        $userProduct = $repo->find($request->get('id'));
        $product = $repository->find($userProduct->getProduct()->getId());
        $user = $this->container->get('security.context')->getToken()->getUser();

        $form = $this->createForm(new UserProductType(), $userProduct);
        $form->add('btn', 'submit', array('label' => 'Valider'));
        $form->handleRequest($request);

        $formProduct = $this->createForm(new AdminProductType(), $product);
        $formProduct->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        }

        return array(
            'form'    => $form->createView(),
            'formProduct' => $formProduct->createView(),
        );
    }

}