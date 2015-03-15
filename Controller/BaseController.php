<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Iphp\CoreBundle\Controller\RubricAwareController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pletnev\ProjectManagerBundle\Entity\Settings;

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
    protected function getEventDispatcher() {
        return $this->get('event_dispatcher');
    }

    protected function addNoticeMessage($message){
        $this->addFlashMessage($message , 'notice');
    }

    protected function addFlashMessage($message , $type='notice'){
        $this->getRequest()->getSession()->getFlashBag()->add($type,$message);
    }

    protected function getSettings(){
        $user = $this->getUser();

        if(!$settings = $this->getSettingsRepo()->findOneBy(array('user'=>$user))){
            $settings = new Settings();
            $settings->setUser($user);
        }

        return $settings;
    }

    protected function getUserManager(){
        return $this->get('fos_user.user_manager');
    }

}
