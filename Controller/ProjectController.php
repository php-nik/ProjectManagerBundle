<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pletnev\ProjectManagerBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Pletnev\ProjectManagerBundle\Form\ProjectType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Application\Sonata\UserBundle\Entity\User;

class ProjectController extends BaseController {

    public function indexAction() {
        return $this->redirectToRoute('pletnev_pm_projects');
    }

    /**
     * @Template
     * @return []
     */
    public function projectAction(Project $project) {
        if (!$this->getProjectManager()->isViewGranted($project)) {
            throw $this->createAccessDeniedException();
        }
        return [
            'project' => $project,
        ];
    }

    /**
     * @Template
     * @return []
     */
    public function projectsAction() {
        /**
         * @var User Description
         */
        $user = $this->getUser();

        $isAdmin = $this->getProjectManager()->isAdmin();

        if ($isAdmin) {
            $projects = $this->getProjectRepo()->findAll();
        } else {
            $projects = $this->getProjectRepo()->createQueryBuilder('p')
                            ->leftJoin('p.members', 'm')
                            ->where('p.author = :user')
                            ->orWhere('m = :user')
                            ->setParameter('user', $user)
                            ->getQuery()->getResult();
        }

        return [
            'projects' => $projects,
        ];
    }

    /**
     *
     * @Template
     */
    public function leftMenuAction($id) {
        return [
            'projects' => $this->getSettings()->getFavoriteProjects(),
            'id' => $id,
        ];
    }

    protected function createProjectForm(Project $project) {
        return $this->createForm(new ProjectType($this->getProjectManager()->isAdmin($project)), $project);
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Template
     */
    public function createAction(Request $request) {
        $user = $this->getUser();

        $project = new Project();
        $project->setAuthor($user);

        $form = $this->createProjectForm($project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $project->setCreateDate(new \Datetime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addNoticeMessage('Проект успешно создан');

            //return $this->redirect($this->generateUrl('pletnev_pm_project', array('projectSlug' => $project->getId())));
            return $this->redirectToRoute('pletnev_pm_project', ['id' => $project->getId()]);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @Template
     */
    public function editAction(Request $request, Project $project) {
        //$user = $this->getUser();

        if (!$this->getProjectManager()->isEditGranted($project)) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createProjectForm($project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            //$project->setCreateDate(new \Datetime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addNoticeMessage('Проект успешно сохранен');

            //return $this->redirect($this->generateUrl('pletnev_pm_project', array('projectId'=>$project->getId())));
        }

        return [
            'project' => $project,
            'form' => $form->createView(),
        ];
    }

    public function favoriteAction(Request $request) {
        $projectId = $request->get('projectId');

        if ($project = $this->getProjectRepo()->find($projectId)) {

            $settings = $this->getSettings();

            if ($settings->getFavoriteProjects()->contains($project)) {
                $settings->removeFavoriteProject($project);
                $content = 'off';
            } else {
                $settings->addFavoriteProject($project);
                $content = 'on';
            }

            $em = $this->getDoctrine()->getManager();

            $em->persist($settings);

            $em->flush();
        } else {
            $content = 'error';
        }

        return new JsonResponse(array(
            'content' => $content,
            'projectId' => $projectId,
        ));
    }

}
