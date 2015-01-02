<?php

namespace Pletnev\ProjectManagerBundle\Form\Author;

use Symfony\Component\Form\FormBuilderInterface;
use Pletnev\ProjectManagerBundle\Form\ProjectType as AdminProjectType;

class ProjectType extends AdminProjectType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
       parent::buildForm($builder, $options);

       $builder
               ->remove('author')
               ->remove('isFavorite');

    }


}
