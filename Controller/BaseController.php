<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Iphp\CoreBundle\Controller\RubricAwareController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pletnev\ProjectManagerBundle\Entity\Settings;
use Pletnev\ProjectManagerBundle\Manager\SettingsManager;
use Pletnev\ProjectManagerBundle\Manager\ProjectManager;
use Pletnev\ProjectManagerBundle\Manager\TaskManager;

class BaseController extends Controller {

    /**
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\ProjectRepository
     */
    protected function getProjectRepo() {
        return $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Project');
    }

    /**
     *
     * @return \Pletnev\ProjectManagerBundle\Entity\SettingsRepository
     */
    protected function getSettingsRepo() {
        return $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Settings');
    }

    /**
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
//    protected function getEventDispatcher() {
//        return $this->get('event_dispatcher');
//    }

    protected function addNoticeMessage($message) {
        $this->addFlash('notice', $message);
    }
    
//    protected function isAdmin()
//    {
//        $user = $this->getUser();
//        return $this->isGranted('ROLE_PM_ADMIN', $user);
//    }

//    protected function addFlashMessage($message , $type='notice'){
//        $this->getRequest()->getSession()->getFlashBag()->add($type,$message);
//    }

    protected function getSettings() {
        return $this->getSettingsManager()->get();
    }

    protected function getUserManager() {
        return $this->get('fos_user.user_manager');
    }

    /**
     * 
     * @return SettingsManager
     */
    protected function getSettingsManager() {
        return $this->get('pletnev_pm.settings_manager');
    }
    
    /**
     * 
     * @return ProjectManager
     */
    protected function getProjectManager() {
        return $this->get('pletnev_pm.project_manager');
    }
    
    /**
     * 
     * @return TaskManager
     */
    protected function getTaskManager() {
        return $this->get('pletnev_pm.task_manager');
    }

}
