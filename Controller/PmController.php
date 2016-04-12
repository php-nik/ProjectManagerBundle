<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Pletnev\ProjectManagerBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pletnev\ProjectManagerBundle\Entity\Settings;
use Pletnev\ProjectManagerBundle\Form\SettingsType;
use Symfony\Component\HttpFoundation\JsonResponse;

//use Symfony\Component\HttpFoundation\Cookie;
//use Symfony\Component\HttpFoundation\Response;
//use Exception;
//use Informika\PrivateOfficeBundle\Form\Type\MessageType;
//use Informika\PrivateOfficeBundle\Entity\Message;
//use Doctrine\ORM\EntityRepository;
//use Symfony\Component\HttpFoundation\Request;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PmController extends BaseController {

    protected function createIndexResponse($redirect) {

        $response = new RedirectResponse($redirect);

        return $response;
    }

    public function indexAction() {

        $roles = $this->getUser()->getRoles();

        $isAdmin = in_array('ROLE_SUPER_ADMIN', $roles) || in_array('ROLE_SONATA_ADMIN', $roles);

        $isPmAuthor = in_array('ROLE_PM_AUTHOR', $roles);
        $isPmAdmin = in_array('ROLE_PM_ADMIN', $roles);
        //$isFederal = in_array('ROLE_FEDERAL_USER', $roles);

        if ($isPmAuthor)
            return $this->createIndexResponse($this->generateUrl('pletnev_pm_author'));

        if ($isPmAdmin)
            return $this->createIndexResponse($this->generateUrl('pletnev_pm_admin'));

        if ($isAdmin)
            return $this->createIndexResponse($this->generateUrl('sonata_admin_dashboard'));

        return $this->createIndexResponse('/');
    }

    public function setUserThemeAction(Request $request) {

        $theme = $request->get('theme');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $settings = $this->getSettings();
        $settings->setTheme($theme);
        //$settings->setIsAjax(false);
        //$settings->setUser($user);
        //$user->setSettings($settings);
        //$em->persist($user);
        //}
        //$settings->setTheme($theme);




        $em->persist($settings);

        $em->flush();

        return new Response('ok');
    }

    public function favoriteProjectAction(Request $request) {
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
