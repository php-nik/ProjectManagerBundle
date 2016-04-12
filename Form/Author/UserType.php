<?php

namespace Pletnev\ProjectManagerBundle\Form\Author;

use Symfony\Component\Form\FormBuilderInterface;
use Pletnev\ProjectManagerBundle\Form\UserType as AdminUserType;

class UserType extends AdminUserType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
    }

}
