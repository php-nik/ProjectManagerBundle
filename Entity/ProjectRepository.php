<?php

namespace Pletnev\ProjectManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Application\Sonata\UserBundle\Entity\User;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends EntityRepository
{

    /**
     *
     * @param \Application\Sonata\UserBundle\Entity\User $author
     * @return \Pletnev\ProjectManagerBundle\Entity\Project[]
     */
    public function getForAuthor(User $author){
        return $this->createQueryBuilder('p')
                ->select('p')
                ->where('p.author = :author')
                ->orWhere(':author MEMBER OF p.members')
                ->setParameter('author', $author)
                ->getQuery()
                ->getResult();
    }
}
