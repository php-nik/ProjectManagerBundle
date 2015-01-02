<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Pletnev\ProjectManagerBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pletnev\ProjectManagerBundle\Form\TaskType;
use Pletnev\ProjectManagerBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Pletnev\ProjectManagerBundle\Form\ProjectType;
use Pletnev\ProjectManagerBundle\Entity\Project;
use Pletnev\ProjectManagerBundle\Event\TaskEvent;
use Pletnev\ProjectManagerBundle\Form\SettingsType;
use Pletnev\ProjectManagerBundle\Form\UserType;

class AdminController extends BaseController {

    /**
     *
     * @Template
     */
    public function indexAction() {
        $user = $this->getUser();

        //$projects = $this->getProjectRepo()->findBy(array('author' => $user));

        return array(
                //'projects' => $projects,
        );
    }

    /**
     *
     * @Template
     */
    public function leftMenuAction($projectSlug) {
        $user = $this->getUser();

        //$projects = $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Project')->findBy(array('isFavorite' => 1));
        $projects = $this->getSettings()->getFavoriteProjects();

        return array(
            'projects' => $projects,
        );
    }

    /**
     * @Template
     */
    public function projectsAction() {
        $projects = $this->getProjectRepo()->findBy(array(), array('title' => 'asc'));

        return array(
            'projects' => $projects,
        );
    }

    /**
     *
     * @Template
     */
    public function projectAction($projectSlug) {
        $user = $this->getUser();

        $project = $this->getProjectRepo()->findOneBy(array('slug' => $projectSlug));

        if (!$project) {
            throw $this->createNotFoundException();
        }

        return array(
            'project' => $project,
        );
    }

    /**
     *
     * @Template
     */
    public function tasksAction(Request $request, $projectSlug) {
        $user = $this->getUser();

        $done = $request->get('done');

        $project = $this->getProjectRepo()->findOneBy(array('slug' => $projectSlug));

        $tasks = $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Task')->createQueryBuilder('t')
                ->where('t.project = :project')->setParameter('project', $project)
                ->join('t.status', 's', 'with', 's.isActive = :active')->setParameter('active', $done ? 0 : 1)
                ->orderBy('t.id', 'DESC')
                ->getQuery()
                ->getResult();

        return array(
            'project' => $project,
            'tasks' => $tasks,
        );
    }

    /**
     *
     * @Template
     */
    public function createTaskAction(Request $request, $projectSlug) {
        $user = $this->getUser();

        $project = $this->getProjectRepo()->findOneBy(array('slug' => $projectSlug));

        $form = $this->createForm(new TaskType($project, $this->getDoctrine()), new Task());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setProject($project);
            $task->setCreateDate(new \Datetime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $this->addNoticeMessage('Задача успешно создана');

            $eventDispatcher = $this->getEventDispatcher();
            $eventDispatcher->dispatch('pm.task.postPersist', new TaskEvent($task, $user));

            return $this->redirect($this->generateUrl('pletnev_pm_admin_tasks', array('projectSlug' => $projectSlug)));
        }

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @Template
     */
    public function taskAction(Request $request, $projectSlug, $taskId) {
        $user = $this->getUser();

        $project = $this->getProjectRepo()->findOneBy(array('slug' => $projectSlug));

        $task = $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Task')->find($taskId);

        return array(
            'project' => $project,
            'task' => $task,
        );
    }

    /**
     *
     * @Template
     */
    public function editTaskAction(Request $request, $projectSlug, $taskId) {
        $user = $this->getUser();

        $project = $this->getProjectRepo()->findOneBy(array('slug' => $projectSlug));

        $task = $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Task')->find($taskId);

        $oldTask = clone $task;

        $form = $this->createForm(new TaskType($project, $this->getDoctrine()), $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();


            $eventDispatcher = $this->getEventDispatcher();
            $eventDispatcher->dispatch('pm.task.preUpdate', new TaskEvent($task, $user, $oldTask));


            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $this->addNoticeMessage('Задача успешно сохранена');

            return $this->redirect($this->generateUrl('pletnev_pm_admin_tasks', array('projectSlug' => $projectSlug)));
        }

        return array(
            'project' => $project,
            'task' => $task,
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Template
     */
    public function createProjectAction(Request $request) {
        $user = $this->getUser();

        $form = $this->createForm(new ProjectType(), new Project());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $project->setCreateDate(new \Datetime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addNoticeMessage('Проект успешно создан');

            return $this->redirect($this->generateUrl('pletnev_pm_admin_project', array('projectSlug' => $project->getId())));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Template
     */
    public function editProjectAction(Request $request, $projectSlug) {
        $user = $this->getUser();

        $project = $this->getProjectRepo()->findOneBy(array('slug'=>$projectSlug));

        $form = $this->createForm(new ProjectType(), $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            //$project->setCreateDate(new \Datetime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addNoticeMessage('Проект успешно сохранен');

            //return $this->redirect($this->generateUrl('pletnev_pm_admin_project', array('projectId'=>$project->getId())));
        }

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @Template
     */
    public function profileAction(Request $request) {
        $user = $this->getUser();

        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getUserManager()->updateUser($user);
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            $this->addNoticeMessage('Данные пользователя успешно сохранены!');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @Template
     */
    public function settingsAction(Request $request) {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $settings = $this->getSettings();

        $form = $this->createForm(new SettingsType(), $settings);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($settings);
            $em->flush();
            $this->addNoticeMessage('Настройки успешно сохранены!');
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
