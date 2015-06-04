<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Form\Type\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SiteController
 * @package CmsUlysseBundle\Controller
 * @Route("/configuration/site")
 */
class SiteController extends Controller
{
    /**
     * @Route("", name="config_view")
     * @Template()
     */
    public function viewAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('CmsUlysseBundle:Site');
        $site = $repository->findOneBy(array());

        return array('site' => $site);
    }

    /**
     * @Route("/add", name="config_add")
     * @Template("CmsUlysseBundle:Site:form.html.twig")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new SiteType());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();
            return $this->redirect($this->generateUrl('config_view'));
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/update", name="config_update")
     * @Template("CmsUlysseBundle:Site:form.html.twig")
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
            return $this->redirect($this->generateUrl('config_view'));
        }
        return array(
            'form' => $form->createView()
        );
    }

}
