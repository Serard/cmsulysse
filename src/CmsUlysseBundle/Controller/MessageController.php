<?php

namespace CmsUlysseBundle\Controller;

use CmsUlysseBundle\Entity\Thread;
use CmsUlysseBundle\Entity\User;
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
            return new RedirectResponse($this->container->get('router')->generate('cms_messagerie_thread_view', array(
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
     * @Template("CmsUlysseBundle:Message:newThread.html.twig")
     */
    public function newThreadAction()
    {
        $form = $this->container->get('fos_message.new_thread_form.factory')->create();
        $formHandler = $this->container->get('fos_message.new_thread_form.handler');

        if ($message = $formHandler->process($form)) {

            $threadId = $message->getThread()->getId();

            $thread = $message->getThread();
            $user = $thread->getCreatedBy();
            $body = $thread->getFirstMessage();
            $subject = $thread->getSubject();
            $receipients = $thread->getOtherParticipants($user);
            $receipient = $receipients[0];
            $this->mailNewThread($user, $receipient, $subject, $thread, $message);

            return new RedirectResponse($this->container->get('router')->generate('cms_messagerie_thread_view', array(
                'threadId' => $threadId
            )));
        }

        return array(
            'form' => $form->createView(),
            'data' => $form->getData()
        );
    }

    /**
     * @Route("/{threadId}/threads", name="cms_messagerie_thread_view")
     * @Template()
     */
    public function threadAction($threadId)
    {
        $thread = $this->getProvider()->getThread($threadId);
        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('cms_messagerie_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return array(
            'form' => $form->createView(),
            'thread' => $thread
        );
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

        return array(
            'query' => $query,
            'threads' => $threads
        );
    }

    public function mailNewThread(User $user, User $recipient, $subject, $thread, $message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($user->getEmail())
            ->setTo($recipient->getEmail())
            ->setBody($this->container->get('templating')->render('CmsUlysseBundle:Mailing:newThread.txt.twig', array('thread' => $thread, 'message' => $message)), 'text/html');

        $this->container->get('mailer')->send($message);
        return $this;
    }

}
