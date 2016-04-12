<?php

namespace Pletnev\ProjectManagerBundle\EventListener;

//use Pletnev\ProjectManagerBundle\Event\TaskEvent;
use Pletnev\ProjectManagerBundle\Entity\Task;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TaskSubscriber implements EventSubscriber {

    protected $container;
    protected $sendFrom;

    public function getSubscribedEvents() {
        return array(
            'postPersist',
            'preUpdate',
        );
    }

    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof Task) {
            $user = $this->getUser();
            $body = $this->renderView('PletnevProjectManagerBundle:Mail:createTask.html.twig', array(
                'task' => $entity,
                'user' => $user
            ));

            $subject = $entity->getProject()->getTitle() . ' Новая задача #' . $entity->getId();

            $message = $this->createMessage($subject, $body)
                    ->setFrom($this->sendFrom)
                    ->setTo($this->getTaskEmails($entity, $user));

            $this->getMailer()->send($message);
        }
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof Task) {
            $user = $this->getUser();
            $uow = $args->getEntityManager()->getUnitOfWork();
            $changes = $uow->getEntityChangeSet($entity);
            $body = $this->renderView('PletnevProjectManagerBundle:Mail:editTask.html.twig', array(
                'task' => $entity,
                'user' => $user,
                'changes' => $changes,
            ));

            $subject = $entity->getProject()->getTitle() . ' Задача #' . $entity->getId() . ' изменена';

            $message = $this->createMessage($subject, $body)
                    ->setFrom($this->sendFrom)
                    ->setTo($this->getTaskEmails($entity, $user));

            $this->getMailer()->send($message);
        }
    }


    /**
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\TaskRepository
     */
    protected function getTaskRepo() {
        return $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Task');
    }

    /**
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\Task $task
     * @param type $user
     * @return array
     */
    protected function getTaskEmails(Task $task, $user) {
        $emails = array();

        if ($assignedTo = $task->getAssignedTo()) {
            $emails[] = $assignedTo->getEmail();
        }

        $emails[] = $user->getEmail();

        foreach ($task->getWatcherUsers() as $watcher) {
            $emails[] = $watcher->getEmail();
        }

        return $emails;
    }

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        if ($this->sendFrom === null) {
            $this->sendFrom = 'pm@pletnev-r.ru';
        }
    }

    /**
     *
     * @return \Symfony\Component\DependencyInjection\Container
     */
    protected function getContainer() {
        return $this->container;
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    protected function renderView($view, array $parameters = array()) {
        return $this->container->get('templating')->render($view, $parameters);
    }

    /**
     *
     * @param string $subject
     * @param string $body
     * @param string $contentType
     * @param string $charset
     * @return \Swift_Message
     */
    protected function createMessage($subject = null, $body = null, $contentType = 'text/html', $charset = null) {
        return \Swift_Message::newInstance($subject, $body, $contentType, $charset);
    }

    /**
     *
     * @return \Swift_Mailer
     */
    protected function getMailer() {
        return $this->getContainer()->get('mailer');
    }

    protected function getDoctrine() {
        return $this->getContainer()->get('doctrine');
    }

    protected function getUser() {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

}
