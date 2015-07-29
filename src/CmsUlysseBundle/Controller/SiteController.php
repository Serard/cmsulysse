<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Site;
use CmsUlysseBundle\Form\Type\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SiteController
 * @package CmsUlysseBundle\Controller
 * @Route("/admin/settings")
 */
class SiteController extends Controller
{
    /**
     * @Route("/home", name="settings_home_admin")
     * @Template("CmsUlysseBundle:Admin:Site/view.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Site');
        $site = $repository->findOneBy(array());

        return array('site' => $site);
    }

    /**
     * @Route("/add", name="settings_add_admin")
     * @Template("CmsUlysseBundle:Admin:Site/form.html.twig")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new SiteType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirect($this->generateUrl('settings_home_admin'));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/update", name="settings_update_admin")
     * @Template("CmsUlysseBundle:Admin:Site/form.html.twig")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CmsUlysseBundle:Site');
        $config = $repo->findOneBy(array(), null, 1);

        $form = $this->createForm(new SiteType(),$config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirect($this->generateUrl('settings_home_admin'));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/remove/img/{id}", name="remove_background_admin")
     * @Template()
     */
    public function removeBackgroundAction(Site $site)
    {
        $em = $this->getDoctrine()->getManager();
        $site->setFile(null);

        $em->persist($site);
        $em->flush();

        return $this->redirect(
            $this->generateUrl('settings_home_admin')
        );
    }

}
