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
        $userProduct = $repo->find($request->get('id'));

        $form = $this->createForm(new UserProductType(), $userProduct);
        $form->add('btn', 'submit', array('label' => 'Valider'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('user_product_list'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/user/product/description/{id}", name="product_user_description")
     * @Template("CmsUlysseBundle:UserProduct:form.html.twig")
     */
    public function descriptionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Product');
        $product = $repo->find($request->get('id'));

        $form = $this->createForm(new AdminProductType(), $product);
        $form->add('valid', null, array('label' => 'Validé : ', 'required' => false, 'disabled' => true));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('user_product_list'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/user/product/{id}", name="product_user_view")
     * @Template("CmsUlysseBundle:UserProduct:view.html.twig")
     */
    public function viewAction(UserProduct $userProduct)
    {

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:UserProduct');
        $products = $repo->findByProduct($userProduct->getProduct());

        return array(
            'userProduct' => $userProduct,
            'products'    => $products,
        );
    }
}