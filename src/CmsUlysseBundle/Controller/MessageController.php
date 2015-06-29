<?php

namespace CmsUlysseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\MessageBundle\Controller\MessageController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/messagerie")
 */
class MessageController extends BaseController
{

    /**
     * @Route("", name="cms_messagerie")
     * @Template()
     */
    public function indexAction()
    {
        $provider = $this->getProvider();
        $threads = $provider->getInboxThreads();

      /*  return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', array(
            'threads' => $threads
        ));

       /* $composer = $this->get('fos_message.composer');
        $message = $composer->newThread()
            ->setSender($this->getUser())
            ->addRecipient($this->getUser())
            ->setSubject('myThread')
            ->setBody('cest le body de mon test')
            ->getMessage();

        $sender = $this->get('fos_message.sender');
        $sender->send($message);

        $provider = $this->get('fos_message.provider');

        $nbUnread = $provider->getNbUnreadMessages();*/

        return array(
            'threads' => $threads,
        );
    }

    /**
     * @Route("", name="cms_messagerie_inbox")
     * @Template()
     */
    public function inboxAction()
    {
        $provider = $this->getProvider();
        $threads = $provider->getInboxThreads();

        return array("threads" => $threads);
    }

    /**
     * @Route("/sent", name="cms_messagerie_sent")
     * @Template()
     */
    public function sentAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');
        $threads = $this->getProvider()->getSentThreads();

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return array(
            "form" => $form->createView(),
            'data' => $form->getData(),
            'threads' => $threads);
    }

    /**
     * @Route("/deleted", name="cms_messagerie_deleted")
     * @Template()
     */
    public function deletedAction()
    {
        $threads = $this->getProvider()->getDeletedThreads();

        return array("threads" => $threads);
    }

    /**
     * @Route("/new", name="cms_messagerie_thread_new")
     * @Template()
     */
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:newThread.html.twig', array(
            'form' => $form->createView(),
            'data' => $form->getData()
        ));
    }

    /**
     * @Route("/threads", name="cms_messagerie_thread_view")
     * @Template()
     */
    public function threadAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:thread.html.twig', array(
            'form' => $form->createView(),
            'thread' => $thread
        ));
    }

    /**
     * @Route("/{threadId}/delete", name="cms_messagerie_thead_delete")
     * @Template()
     */
    public function deleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsDeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('cms_messagerie_inbox'));
    }

    /**
     * @Route("/{threadId}/undelete", name="cms_messagerie_thread_undelete")
     * @Template()
     */
    public function undeleteAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $this->container->get('fos_message.deleter')->markAsUndeleted($thread);
        $this->container->get('fos_message.thread_manager')->saveThread($thread);

        return new RedirectResponse($this->container->get('router')->generate('cms_messagerie_inbox'));
    }

    /**
     * @Route("/search", name="cms_messagerie_search")
     * @Template()
     */
    public function searchAction()
    {
        $query = $this->container->get('fos_message.search_query_factory')->createFromRequest();
        $threads = $this->container->get('fos_message.search_finder')->find($query);

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:search.html.twig', array(
            'query' => $query,
            'threads' => $threads
        ));
    }

}
