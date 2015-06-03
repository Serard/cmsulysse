<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Category;
use CmsUlysseBundle\Form\Type\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/list", name="category_list")
     * @Template()
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Category');
        $categories  = $repo->findCategsUp();
        $categs_down = $repo->findCategsDown();

        return array(
                'categories'  => $categories,
                'categs_down' => $categs_down
            );
    }

    /**
     * @Route("/add", name="category_add")
     * @Template("CmsUlysseBundle:Category:form.html.twig")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new CategoryType(), new Category());
        $form->add('btn', 'submit', array('label' => 'Ajouter'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('category_list'));
        }

        return array(
                'title' => 'Ajouter une catégorie',
                'form'  => $form->createView()
            );
    }

    /**
     * @Route("/update/{id}", name="category_update")
     * @Template("CmsUlysseBundle:Category:form.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Category');
        $category = $repo->find($request->get('id'));

        $form = $this->createForm(new CategoryType(), $category);
        $form->add('btn', 'submit', array('label' => 'Modifier'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('category_list'));
        }

        return array(
                'title' => 'Modifier une catégorie',
                'form'  => $form->createView()
            );
    }


    /**
     * @Route("/delete/{id}", name="category_delete")
     */
    public function deleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Category');
        $category = $repo->find($request->get('id'));

        $categs_down = $repo->findBy(array('categ_up' => $category->getId()));
        foreach ($categs_down as $categ_down) {
            $categ_down->setCategUp(null);
            $em->persist($categ_down);
            $em->flush();
        }

        $em->remove($category);
        $em->flush();



        return $this->redirect($this->generateUrl('category_list'));
    }

}
