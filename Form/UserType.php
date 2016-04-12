<?php

namespace Pletnev\ProjectManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username' , null , array('disabled'=>true))
                ->add('email' , 'email')
                ->add('plainPassword' , null , array(
                    'required'=>false
                ))
                ->add('save', 'submit', array('label' => 'Сохранить'));
    }

    public function getName() {
        return '';
    }

}
