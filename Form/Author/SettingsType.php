<?php

namespace Pletnev\ProjectManagerBundle\Form\Author;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Pletnev\ProjectManagerBundle\Form\SettingsType as AdminSettingsType;

class SettingsType extends AdminSettingsType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        $builder->remove('favoriteProjects');
    }

}
