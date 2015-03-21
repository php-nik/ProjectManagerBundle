<?php

namespace Pletnev\ProjectManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('slug')
                ->add('url' , 'url')
                ->add('author')
                ->add('description' , 'textarea' , array(
                    'attr'=>array('class'=>'tinymce')
                ))
                ->add('members' , null , array(
                    'expanded'=>TRUE,
                ))
                ->add('save', 'submit', array('label' => 'Сохранить'));
    }

    public function getName() {
        return '';
    }

}
