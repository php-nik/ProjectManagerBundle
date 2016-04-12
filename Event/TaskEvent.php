<?php

namespace Pletnev\ProjectManagerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Pletnev\ProjectManagerBundle\Entity\Task;

class TaskEvent extends Event {

    /**
     *
     * @var \Pletnev\ProjectManagerBundle\Entity\Task
     */
    protected $task;

    /**
     *
     * @var \Pletnev\ProjectManagerBundle\Entity\Task
     */
    protected $oldTask;
    protected $user;

    public function __construct(Task $task, $user, Task $oldTask = null) {
        $this->task = $task;
        $this->user = $user;
        $this->oldTask = $oldTask;
    }

    /**
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\Task
     */
    public function getTask() {
        return $this->task;
    }

    public function getUser() {
        return $this->user;
    }

    public function setOldTask(Task $task) {
        $this->oldTask = $task;
        return $this;
    }

    public function getOldTask() {
        return $this->oldTask;
    }

}
