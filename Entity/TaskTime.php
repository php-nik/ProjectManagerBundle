<?php

namespace Pletnev\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskTime
 */
class TaskTime {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $start;
    private $stop;
    private $task;

    public function __construct() {
        $this->start = new \Datetime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getStart() {
        return $this->start;
    }

    public function setStart($start) {
        $this->start = $start;
        return $this;
    }

    public function getStop() {
        return $this->stop;
    }

    public function setStop($stop) {
        $this->stop = $stop;
        return $this;
    }

    public function setTask(Task $task) {
        $this->task = $task;
    }

    public function getTask() {
        return $this->task;
    }

}
