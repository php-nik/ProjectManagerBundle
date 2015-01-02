<?php

namespace Pletnev\ProjectManagerBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Pletnev\ProjectManagerBundle\Entity\Project;

class ProjectEvent extends Event {

    /**
     *
     * @var \Pletnev\ProjectManagerBundle\Entity\Project
     */
    protected $project;
    protected $user;

    public function __construct(Project $project, $user) {
        $this->project = $project;
        $this->user = $user;
    }

    /**
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\Project
     */
    public function getProject() {
        return $this->project;
    }

    public function getUser() {
        return $this->user;
    }

}
