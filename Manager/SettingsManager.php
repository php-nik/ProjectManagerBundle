<?php

namespace Pletnev\ProjectManagerBundle\Manager;

use Doctrine\ORM\EntityManager;
use Pletnev\ProjectManagerBundle\Entity\Settings;

class SettingsManager extends Manager{

    protected $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function save(Settings $settings, $flush = true) {
        $this->em->persist($settings);

        if ($flush) {
            $this->em->flush($settings);
        }
    }

    public function get() {
        $user = $this->getUser();
        if (!$settings = $this->getSettingsRepo()->findOneBy(['user' => $user])) {
            $settings = new Settings();
            $settings->setUser($user);
            $this->save($settings);
        }
        return $settings;
    }

    /**
     * 
     * @return \Pletnev\ProjectManager\Entity\SettingsRepository
     */
    protected function getSettingsRepo() {
        return $this->em->getRepository('PletnevProjectManagerBundle:Settings');
    }

}
