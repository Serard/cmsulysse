<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserProductController extends Controller
{
    /**
     * @Route("/market")
     * @Template()
     */
    public function listAction()
    {
        $products = $this->getDoctrine()->getManager()->getRepository('CmsUlysseBundle:UserProduct')->findAll();

        return array(
                'products' => $products
            );
    }

}
