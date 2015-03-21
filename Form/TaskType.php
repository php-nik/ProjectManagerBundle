<?php

namespace Pletnev\ProjectManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Pletnev\ProjectManagerBundle\Entity\Project;

class TaskType extends AbstractType {

    protected $doneRationChoices=array();
    /**
     *
     * @var \Pletnev\ProjectManagerBundle\Entity\Project
     */
    protected $project;

    protected $doctrine;

    public function __construct(Project $project , $doctrine) {
        $this->project=$project;

        $this->doctrine=$doctrine;

        for($i=0;$i<=100;$i+=10){
            $this->doneRationChoices[$i]=$i.' %    ';
        }

    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $users = $this->project->getMembers();
        $builder
                ->add('title')
                ->add('status' , null , array('required'=>true))
                ->add('priority' , null , array(
                    'data'=>  $this->getDefaultPriority(),
                    'required'=>true,
                ))
                ->add('assignedTo' , null , array(
                    'query_builder'=>function($repo) use($users){
                        return $repo->createQueryBuilder('u')
                                ->where('u in (:users)')
                                ->setParameter('users' , $users->toArray())
                                ;
                    }
                ))
                ->add('minutes' , null , array(
                    'required'=>false
                ))
                ->add('isPaid' , null , array(
                    'required'=>false
                ))
                ->add('doneRation' , 'choice' , array(
                    'choices'=>  $this->doneRationChoices,
                ))
                ->add('description' , 'textarea' , array(
                    'attr'=>array('class'=>'tinymce')
                ))
                ->add('watcherUsers' , null , array(
                    'expanded'=>true,
                    'query_builder'=>function($repo) use($users){
                        return $repo->createQueryBuilder('u')
                                ->where('u in (:users)')
                                ->setParameter('users' , $users->toArray())
                                ;
                    }
                ))
                ->add('save', 'submit', array('label' => 'Сохранить'));
    }

    public function getName() {
        return '';
    }

    protected function getDefaultPriority(){
        return $this->doctrine->getRepository('PletnevProjectManagerBundle:TaskPriority')->findOneBy(array('title'=>'нормальный'));
    }

}
