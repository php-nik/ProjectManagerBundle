<?php

namespace Pletnev\ProjectManagerBundle\EventListener;

use Pletnev\ProjectManagerBundle\Event\TaskEvent;
use Pletnev\ProjectManagerBundle\Entity\Task;

class TaskSubscriber extends AbstractSubscriber {

    public static function getSubscribedEvents() {
        return array(
            'pm.task.postPersist' => array('onPostPersist', 0),
            'pm.task.preUpdate' => array('onPreUpdate' , 0),
        );
    }

    public function onPostPersist(TaskEvent $event) {
        $task = $event->getTask();
        $user = $event->getUser();

        $body = $this->renderView('PletnevProjectManagerBundle:Mail:createTask.html.twig', array(
            'task' => $task,
            'user' => $user
        ));

        $subject = $task->getProject()->getTitle() . ' Новая задача #' . $task->getId();

        $message = $this->createMessage($subject, $body)
                ->setFrom($this->sendFrom)
                ->setTo($this->getTaskEmails($task, $user));

        $this->getMailer()->send($message);
    }

    public function onPreUpdate(TaskEvent $event) {
        $task = $event->getTask();
        $oldTask = $event->getOldTask();
        $user = $event->getUser();

        $body = $this->renderView('PletnevProjectManagerBundle:Mail:editTask.html.twig', array(
            'task' => $task,
            'user' => $user,
            'oldTask'=>$oldTask,
        ));

        $subject = $task->getProject()->getTitle() . ' Задача #' . $task->getId().' изменена';

        $message = $this->createMessage($subject, $body)
                ->setFrom($this->sendFrom)
                ->setTo($this->getTaskEmails($task, $user));

        $this->getMailer()->send($message);
    }
    /**
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\TaskRepository
     */
    protected function getTaskRepo(){
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

}
