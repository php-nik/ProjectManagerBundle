<?php

namespace Pletnev\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Task
 */
class Task {

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $createDate;

    /**
     * @var \DateTime
     */
    private $dueDate;

    /**
     * @var float
     */
    private $hours;

    /**
     * @var integer
     */
    private $doneRation;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Pletnev\ProjectManagerBundle\Entity\Project
     */
    private $project;

    /**
     * @var \Pletnev\ProjectManagerBundle\Entity\TaskStatus
     */
    private $status;

    /**
     * @var \Pletnev\ProjectManagerBundle\Entity\TaskPriority
     */
    private $priority;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $assignedTo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $watcherUsers;
    private $times;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $author;

    /**
     * @var integer
     */
    private $minutes;

    /**
     * @var boolean
     */
    private $isPaid;

    /**
     * Constructor
     */
    public function __construct() {
        $this->watcherUsers = new ArrayCollection();
        $this->times = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Task
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Task
     */
    public function setCreateDate($createDate) {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get createDate
     *
     * @return \DateTime
     */
    public function getCreateDate() {
        return $this->createDate;
    }

    /**
     * Set dueDate
     *
     * @param \DateTime $dueDate
     * @return Task
     */
    public function setDueDate($dueDate) {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate
     *
     * @return \DateTime
     */
    public function getDueDate() {
        return $this->dueDate;
    }

    /**
     * Set hours
     *
     * @param float $hours
     * @return Task
     */
    public function setHours($hours) {
        $this->hours = $hours;
        $this->minutes = intval($hours * 60);
        return $this;
    }

    /**
     * Get hours
     *
     * @return float
     */
    public function getHours() {
        if ($this->hours === null) {
            $this->hours = intval($this->minutes) / 60;
        }
        return $this->hours;
    }

    /**
     * Set doneRation
     *
     * @param integer $doneRation
     * @return Task
     */
    public function setDoneRation($doneRation) {
        $this->doneRation = $doneRation;

        return $this;
    }

    /**
     * Get doneRation
     *
     * @return integer
     */
    public function getDoneRation() {
        return $this->doneRation;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set project
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\Project $project
     * @return Task
     */
    public function setProject(\Pletnev\ProjectManagerBundle\Entity\Project $project = null) {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\Project
     */
    public function getProject() {
        return $this->project;
    }

    /**
     * Set status
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\TaskStatus $status
     * @return Task
     */
    public function setStatus(\Pletnev\ProjectManagerBundle\Entity\TaskStatus $status = null) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\TaskStatus
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set priority
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\TaskPriority $priority
     * @return Task
     */
    public function setPriority(\Pletnev\ProjectManagerBundle\Entity\TaskPriority $priority = null) {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\TaskPriority
     */
    public function getPriority() {
        return $this->priority;
    }

    /**
     * Set assignedTo
     *
     * @param \Application\Sonata\UserBundle\Entity\User $assignedTo
     * @return Task
     */
    public function setAssignedTo(\Application\Sonata\UserBundle\Entity\User $assignedTo = null) {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getAssignedTo() {
        return $this->assignedTo;
    }

    /**
     * Add watcherUsers
     *
     * @param \Application\Sonata\UserBundle\Entity\User $watcherUsers
     * @return Task
     */
    public function addWatcherUser(\Application\Sonata\UserBundle\Entity\User $watcherUsers) {
        $this->watcherUsers[] = $watcherUsers;

        return $this;
    }

    /**
     * Remove watcherUsers
     *
     * @param \Application\Sonata\UserBundle\Entity\User $watcherUsers
     */
    public function removeWatcherUser(\Application\Sonata\UserBundle\Entity\User $watcherUsers) {
        $this->watcherUsers->removeElement($watcherUsers);
    }

    /**
     * Get watcherUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWatcherUsers() {
        return $this->watcherUsers;
    }

    public function isActive() {
        return $this->getStatus() ? $this->getStatus()->getIsActive() : null;
    }

    public function __toString() {
        return (string) $this->title;
    }

    /**
     * Set isPaid
     *
     * @param boolean $isPaid
     * @return Task
     */
    public function setIsPaid($isPaid) {
        $this->isPaid = $isPaid;

        return $this;
    }

    /**
     * Get isPaid
     *
     * @return boolean
     */
    public function getIsPaid() {
        return $this->isPaid;
    }

    /**
     * Set minutes
     *
     * @param integer $minutes
     * @return Task
     */
    public function setMinutes($minutes) {
        $this->minutes = $minutes;

        return $this;
    }

    /**
     * Get minutes
     *
     * @return integer
     */
    public function getMinutes() {
        return $this->minutes;
    }

    public function getTimes() {
        return $this->times;
    }

    public function setTimes($times) {
        $this->times = $times;
        return $this;
    }

    public function addTime(TaskTime $time) {
        if (!$this->times->contains($time))
            $this->times->add($time);
    }

    public function getOpenTime() {
        if ($this->times->count() && $this->times->last()->getStop() == null) {
            return $this->times->last();
        }
        return null;
    }

    /**
     *
     * @return \DateInterval
     */
    public function getTotalTime() {
        $reference = new \DateTime();
        $endTime = clone $reference;

        foreach ($this->times as $time) {
            if ($time->getStart() && $time->getStop()) {
                $dateInterval = $time->getStart()->diff($time->getStop());
                $endTime = $endTime->add($dateInterval);
            }
        }

        return $reference->diff($endTime);
    }

    /**
     * Remove time
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\TaskTime $time
     */
    public function removeTime(\Pletnev\ProjectManagerBundle\Entity\TaskTime $time) {
        $this->times->removeElement($time);
    }

    /**
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     *
     * @return Task
     */
    public function setAuthor(\Application\Sonata\UserBundle\Entity\User $author = null) {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getAuthor() {
        return $this->author;
    }

}
