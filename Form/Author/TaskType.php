<?php

namespace Pletnev\ProjectManagerBundle\Form\Author;

use Symfony\Component\Form\FormBuilderInterface;
use Pletnev\ProjectManagerBundle\Form\TaskType as AdminTaskType;

class TaskType extends AdminTaskType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
    }

}
