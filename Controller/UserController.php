<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pletnev\ProjectManagerBundle\Entity\Project;
use Pletnev\ProjectManagerBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pletnev\ProjectManagerBundle\Form\TaskType;

class UserController extends BaseController {

    public function setThemeAction(Request $request) {
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

}
