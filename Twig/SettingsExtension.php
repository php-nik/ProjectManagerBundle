<?php

namespace Pletnev\ProjectManagerBundle\Twig;

use Pletnev\ProjectManagerBundle\Manager\SettingsManager;

class SettingsExtension extends \Twig_Extension {

    protected $settingsManager;
    protected $container;

    public function getName() {
        return 'pm_settings';
    }

    public function __construct(SettingsManager $settingsManager) {
        $this->settingsManager = $settingsManager;
    }

    public function getFunctions() {
        return array(
            'settings' => new \Twig_Function_Method($this, 'settings')
        );
    }

    public function settings() {
        return $this->settingsManager->get();
    }

}
