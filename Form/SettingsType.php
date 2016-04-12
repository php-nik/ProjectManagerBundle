<?php

namespace Pletnev\ProjectManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SettingsType extends AbstractType {

    public static $themeChoices = array(
        'classic' => 'Classic',
        'cerulean' => 'Cerulean',
        'cyborg' => 'Cyborg',
        'simplex' => 'Simplex',
        'darkly' => 'Darkly',
        'lumen' => 'Lumen',
        'slate' => 'Slate',
        'spacelab' => 'Spacelab',
        'united' => 'United',
    );

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('theme', 'choice', array(
                    'choices' => self::$themeChoices,
                ))
                ->add('favoriteProjects')
                ->add('save', 'submit', array('label' => 'Сохранить'));
    }

    public function getName() {
        return '';
    }

}
