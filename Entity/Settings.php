<?php

namespace Pletnev\ProjectManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Settings
 */
class Settings {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $theme;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set theme
     *
     * @param string $theme
     * @return Settings
     */
    public function setTheme($theme) {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme() {
        return $this->theme;
    }

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $user;

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Settings
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $favoriteProjects;

    /**
     * Constructor
     */
    public function __construct() {
        $this->favoriteProjects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add favoriteProjects
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\Project $favoriteProjects
     * @return Settings
     */
    public function addFavoriteProject(\Pletnev\ProjectManagerBundle\Entity\Project $favoriteProjects) {
        $this->favoriteProjects[] = $favoriteProjects;

        return $this;
    }

    /**
     * Remove favoriteProjects
     *
     * @param \Pletnev\ProjectManagerBundle\Entity\Project $favoriteProjects
     */
    public function removeFavoriteProject(\Pletnev\ProjectManagerBundle\Entity\Project $favoriteProjects) {
        $this->favoriteProjects->removeElement($favoriteProjects);
    }

    /**
     * Get favoriteProjects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavoriteProjects() {
        return $this->favoriteProjects;
    }

}
