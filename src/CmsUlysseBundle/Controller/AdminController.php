<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Product;
use CmsUlysseBundle\Entity\Site;
use CmsUlysseBundle\Entity\Slider;
use CmsUlysseBundle\Entity\User;
use CmsUlysseBundle\Form\Type\AdminProductType;
use CmsUlysseBundle\Form\Type\CommunityManagerType;
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
        $repo = $this->getDoctrine()->getManager()->getRepository('CmsUlysseBundle:UserProduct');
        $userProducts = $repo->findByProduct($product);

        return array(
            'product' => $product,
            'userProducts' => $userProducts
        );
    }

    /**
     * @Route("/modules", name="modules_admin")
     * @Template("CmsUlysseBundle:Admin:Module/index.html.twig")
     */
    public function modulesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Site');
        $site = $repository->findOneBy(array());

        $form = $this->createForm(new CommunityManagerType(), $site);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slider = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();
        }

        return array(
            'site' => $site,
            'form' => $form->createView(),
        );
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

    /**
     * @Route("/config/communityManager/{id}/edit", name="active_cm_admin")
     * @Template()
     */
    public function activeCmAction(Site $site)
    {
        $site->setCmActive(!$site->getCmActive());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('modules_admin');
    }

    /** @Route("/config/nuggets/{id}/edit", name="active_nuggets")
     * @Template()
     */
    public function active_nuggetsAction(Site $site)
    {
        $site->setNuggets(!$site->getNuggets());
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('modules_admin');
    }

    /**
     * @Route("/users", name="list_users")
     * @Template("CmsUlysseBundle:Admin:User/list.html.twig")
     */
    public function listUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:User');
        $users = $repo->findAll();

        return array('users' => $users);
    }

    /**
     * @Route("/user/desactive/{id}", name="desactive_user")
     * @Template()
     */
    public function desactiveUserAction(User $user)
    {
        $user->setEnabled(! $user->isEnabled());
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('list_users'));
    }

    /**
     * @Route("/user/{id}", name="user_view")
     * @Template("CmsUlysseBundle:Admin:User/view.html.twig")
     */
    public function viewUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:UserProduct');
        $userProducts = $repo->findByUser($user);

        return array(
            'user' => $user,
            'userProducts' => $userProducts
        );
    }

    /**
     * @Route("/user/promote/{id}", name="promote_user")
     * @Template()
     */
    public function promoteUserAction(User $user)
    {
        $user->addRole('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('user_view', array('id' => $user->getId())));
    }

    /**
     * @Route("/user/remove/{id}", name="remove_user")
     * @Template()
     */
    public function removeUserAction(User $user)
    {
        $user->removeRole('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('user_view', array('id' => $user->getId())));
    }

}
