<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/messagerie")
 */
class MessageController extends Controller
{
    /**
     * @Route("")
     * @Template()
     */
    public function indexAction()
    {
        $composer = $this->get('fos_message.composer');
        $message = $composer->newThread()
            ->setSender($this->getUser())
            ->addRecipient($this->getUser())
            ->setSubject('myThread')
            ->setBody('cest le body de mon test')
            ->getMessage();

        $sender = $this->get('fos_message.sender');
        $sender->send($message);

        $provider = $this->get('fos_message.provider');

        $nbUnread = $provider->getNbUnreadMessages();

        return array(
            'message' => $message,
            'nbUnread' => $nbUnread
            );
    }

}
