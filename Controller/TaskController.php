<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pletnev\ProjectManagerBundle\Entity\Project;
use Pletnev\ProjectManagerBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Pletnev\ProjectManagerBundle\Form\TaskType;

class TaskController extends BaseController {

    public function indexAction() {
        return $this->redirectToRoute('pletnev_pm_projects');
    }

    /**
     * @Template
     * @return []
     */
    public function tasksAction(Request $request, Project $project) {
        if (!$this->getProjectManager()->isViewGranted($project)) {
            throw $this->createAccessDeniedException();
        }

        $done = $request->get('done');

        $tasks = $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Task')->createQueryBuilder('t')
                ->where('t.project = :project')->setParameter('project', $project)
                ->join('t.status', 's', 'with', 's.isActive = :active')->setParameter('active', $done ? 0 : 1)
                ->orderBy('t.id', 'DESC')
                ->getQuery()
                ->getResult();

        return [
            'project' => $project,
            'tasks' => $tasks,
        ];
    }

    /**
     * @Template
     * @return []
     */
    public function taskAction(Task $task) {

        if (!$this->getTaskManager()->isViewGranted($task)) {
            throw $this->createAccessDeniedException();
        }

        $project = $task->getProject();

        return [
            'task' => $task,
            'project' => $project,
        ];
    }

    protected function createProjectForm(Project $project, Task $task) {
        return $this->createForm(new TaskType($project, $this->getDoctrine()), $task);
    }

    protected function createTask(Project $project) {
        $task = new Task();
        $task
                ->setAuthor($this->getUser())
                ->setProject($project)
                ->setCreateDate(new \Datetime());
        return $task;
    }

    /**
     * @Template
     * @param Request $request
     * @param Project $project
     * @return type
     */
    public function createAction(Request $request, Project $project) {
        if (!$this->getProjectManager()->isViewGranted($project)) {
            throw $this->createAccessDeniedException();
        }

        $task = $this->createTask($project);
        $form = $this->createProjectForm($project, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getTaskManager()->save($task);
            $this->addNoticeMessage('Задача успешно создана');
            return $this->redirectToRoute('pletnev_pm_tasks', ['id' => $project->getId()]);
        }

        return array(
            'project' => $project,
            'form' => $form->createView(),
        );
    }

    /**
     * 
     * @param Request $request
     * @param Task $task
     * @return type
     * @Template
     */
    public function editAction(Request $request, Task $task) {


        //$oldTask = clone $task;

        $project = $task->getProject();

        $form = $this->createForm(new TaskType($project, $this->getDoctrine()), $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$task = $form->getData();
            //$eventDispatcher = $this->getEventDispatcher();
            //$eventDispatcher->dispatch('pm.task.preUpdate', new TaskEvent($task, $user, $oldTask));


            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            $this->addNoticeMessage('Задача успешно сохранена');

            //return $this->redirect($this->generateUrl('pletnev_pm_tasks', array('projectSlug' => $projectSlug)));
            return $this->redirectToRoute('pletnev_pm_tasks', ['id' => $project->getId()]);
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
     * @param type $taskId
     * @return type
     * @Template
     */
    public function timesAction(Request $request, $taskId) {
        $action = $request->get('action');

        $task = $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Task')->find($taskId);
        $em = $this->getDoctrine()->getManager();

        if ($action == 'start' && $task->getOpenTime() == null) {
            $time = new TaskTime();
            $time->setTask($task);
            $task->addTime($time);
            $em->persist($task);
            $em->flush();
        } elseif ($action == 'stop' && ($time = $task->getOpenTime())) {
            $time->setStop(new \Datetime());
            $em->persist($time);
            $em->flush();
        }

        return array(
            'task' => $task,
        );
    }

}
