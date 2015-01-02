<?php

namespace Pletnev\ProjectManagerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Pletnev\ProjectManagerBundle\Entity\TaskStatus;
use Pletnev\ProjectManagerBundle\Entity\TaskPriority;

class PmInstallCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('pm:install');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln('start install');
        $em=  $this->getDoctrine()->getManager();

        $statuses=array(
            'Новая'=>array('isActive'=>1 , 'class'=>'label-warning'),
            'В работе'=>array('isActive'=>1 , 'class'=>'label-info'),
            'Решена'=>array('isActive'=>1 , 'class'=>'label-success'),
            'Отправлена на доработку'=>array('isActive'=>1 , 'class'=>'label-danger'),
            'Решена и проверена'=>array('isActive'=>0 , 'class'=>'label-success'),
            'Отложена'=>array('isActive'=>0 , 'class'=>'label-warning'),
        );

        $taskStatusRepo =  $this->getTaskStatusRepo();


        foreach ($statuses as $title=>$statusArr){
            if(!$status=$taskStatusRepo->findOneBy(array('title'=>$title))){
                $status=new TaskStatus();
            }
            $status->setIsActive($statusArr['isActive']);
            $status->setClass($statusArr['class']);
            $status->setTitle($title);
            $em->persist($status);
        }

        $priorities=array(
            'низкий',
            'нормальный',
            'высокий',
            'срочный',
            'немедленный'
        );

        $taskPriorityRepo = $this->getTaskPriorityRepo();


        foreach ($priorities as $title){
            if(!$priority=$taskPriorityRepo->findOneBy(array('title'=>$title))){
                $priority=new TaskPriority();
            }

            $priority->setTitle($title);
            $em->persist($priority);
        }

        $em->flush();

        $output->writeln('install successfull');

    }
    /**
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine(){
        return $this->getContainer()->get('doctrine');
    }

    protected function getTaskStatusRepo(){
        return $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:TaskStatus');
    }

    protected function getTaskPriorityRepo(){
        return $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:TaskPriority');
    }



}
