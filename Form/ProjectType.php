<?php

namespace Pletnev\ProjectManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectType extends AbstractType {

    protected $isAdmin;

    public function __construct($isAdmin) {
        $this->isAdmin = (bool) $isAdmin;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title')
                ->add('url', 'url', [
                    'required' => false
                ]);

        if ($this->isAdmin) {
            $builder->add('author');
        }

        $builder
                ->add('description', 'textarea', array(
                    'attr' => array('class' => 'tinymce')
                ))
                ->add('members', null, array(
                    'expanded' => TRUE,
                ))
                ->add('save', 'submit', array('label' => 'Сохранить'));
    }

    public function getName() {
        return '';
    }

}
