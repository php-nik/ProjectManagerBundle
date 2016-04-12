<?php

namespace Pletnev\ProjectManagerBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerAware;

abstract class Manager extends ContainerAware {

    protected function getUser() {
        if (!$this->container->has('security.context')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.context')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }

    protected function isGranted($attributes, $object = null) {
        if (!$this->container->has('security.authorization_checker')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        return $this->container->get('security.authorization_checker')->isGranted($attributes, $object);
    }

}
