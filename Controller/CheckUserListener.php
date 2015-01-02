<?php

namespace Pletnev\ProjectManagerBundle\Controller;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Проверка права доступ на интерфейс оператора или региона
 */
class CheckUserListener {

    public function onKernelController(FilterControllerEvent $event) {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        $checkControllers = array(
            '\\Pletnev\\ProjectManagerBundle\\Controller\\AdminController' => 'ROLE_PM_ADMIN',
            '\\Pletnev\\ProjectManagerBundle\\Controller\\AuthorController' => 'ROLE_PM_AUTHOR',
        );

        $checked = false;

        foreach ($checkControllers as $className => $role) {
            if (is_a($controller[0], $className) && !in_array($role, $controller[0]->getUser()->getRoles())) {
                $redirectUrl = $controller[0]->generateUrl('pletnev_pm');
                $event->setController(function () use ($redirectUrl) {
                    return new RedirectResponse($redirectUrl);
                });
            }
        }
    }

}
