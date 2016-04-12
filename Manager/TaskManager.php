<?php

namespace Pletnev\ProjectManagerBundle\Manager;

use Pletnev\ProjectManagerBundle\Entity\Task;
use Doctrine\ORM\EntityManager;
use Pletnev\ProjectManagerBundle\Manager\ProjectManager;

use Pletnev\ProjectManagerBundle\Event\TaskEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TaskManager extends Manager {

    protected $em;
    protected $projectManager;
    protected $eventDispatcher;

    public function __construct(EntityManager $em , ProjectManager $projectManager , EventDispatcherInterface $dispatcher) {
        $this->em = $em;
        $this->projectManager = $projectManager;
        $this->eventDispatcher = $dispatcher;
    }

    public function save(Task $task, $flush = true) {
        $this->em->persist($task);

        if ($flush) {
            $this->em->flush($task);
        }
        
        $this->eventDispatcher->dispatch('pm.task.postPersist', new TaskEvent($task, $this->getUser()));
    }

    
    public function isViewGranted(Task $task) {
        $project = $task->getProject();
        return $this->projectManager->isViewGranted($project);
    }
    
    public function isEditGranted(Task $task){
        $project = $task->getProject();
        return $this->projectManager->isEditGranted($project) || $this->isAuthor($task);
    }
    
    public function isRemoveGranted(Task $task){
        
    }
    
    public function isAuthor(Task $task){
        return $task->getAuthor() === $this->getUser();
    }

}
