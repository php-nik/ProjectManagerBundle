<?php

namespace Pletnev\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Project
 */
class Project {

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTime
     */
    private $createDate;

    /**
     * @var string
     */
    private $description;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $author;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $members;
    protected $activeTasks;

    /**
     * Constructor
     */
    public function __construct() {
        $this->members = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Project
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
     * Set url
     *
     * @param string $url
     * @return Project
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set createDate
     *
     * @param \DateTime $createDate
     * @return Project
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
     * Set description
     *
     * @param string $description
     * @return Project
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
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     * @return Project
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

    /**
     * Add members
     *
     * @param \Application\Sonata\UserBundle\Entity\User $members
     * @return Project
     */
    public function addMember(\Application\Sonata\UserBundle\Entity\User $members) {
        $this->members[] = $members;

        return $this;
    }

    public function setMembers($members) {
        $this->members = $members;
        return $this;
    }

    /**
     * Remove members
     *
     * @param \Application\Sonata\UserBundle\Entity\User $members
     */
    public function removeMember(\Application\Sonata\UserBundle\Entity\User $members) {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembers() {
        return $this->members;
    }

    public function __toString() {
        return $this->getTitle();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tasks;

    /**
     * Add tasks
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\Task $tasks
     * @return Project
     */
    public function addTask(\Pletnev\ProjectManagerBundle\Entity\Task $tasks) {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\Task $tasks
     */
    public function removeTask(\Pletnev\ProjectManagerBundle\Entity\Task $tasks) {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\Task[]
     */
    public function getTasks() {
        return $this->tasks;
    }

    public function getActiveTasks() {
        if ($this->activeTasks === null) {
            $this->activeTasks = new ArrayCollection();

            foreach ($this->getTasks() as $task) {
                if ($task->isActive()) {
                    $this->activeTasks[] = $task;
                }
            }
        }

        return $this->activeTasks;
    }

    public function countActiveTasks() {
        return $this->getActiveTasks()->count();
    }

    /**
     * @var string
     */
    private $slug;

    /**
     * Set slug
     *
     * @param string $slug
     * @return Project
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

}
