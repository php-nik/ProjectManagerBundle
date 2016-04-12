<?php

namespace Pletnev\ProjectManagerBundle\Manager;

use Pletnev\ProjectManagerBundle\Entity\Project;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class ProjectManager extends Manager {

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Просматривать проект может админ, автор или участник проекта
     * @param Project $project
     * @return boolean
     */
    public function isViewGranted(Project $project) {
        return $this->isAdmin() || $this->isAuthor($project) || $this->isMember($project);
    }

    /**
     * Редактировать проект может админ или автор проекта
     * @param Project $project
     * @return boolean
     */
    public function isEditGranted(Project $project) {
        return $this->isAdmin() || $this->isAuthor($project);
    }

    public function isRemoveGranted(Project $project) {
        return $this->isAdmin() || $this->isAuthor($project);
    }

//
//    protected function getUser() {
//        if (!$this->container->has('security.context')) {
//            throw new \LogicException('The SecurityBundle is not registered in your application.');
//        }
//
//        if (null === $token = $this->container->get('security.context')->getToken()) {
//            return;
//        }
//
//        if (!is_object($user = $token->getUser())) {
//            return;
//        }
//
//        return $user;
//    }

    public function isAdmin($user = null) {
        return $this->isGranted('ROLE_PM_ADMIN', $user ? $user : $this->getUser());
    }

    public function isAuthor(Project $project) {
        return $project->getAuthor() === $this->getUser();
    }

    public function isMember(Project $project) {
        return $project->getMembers()->contains($this->getUser());
    }

}
