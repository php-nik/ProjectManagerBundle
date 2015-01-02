<?php

namespace Pletnev\ProjectManagerBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SettingsExtension extends \Twig_Extension {

    protected $container;

    public function getName() {
        return 'pm_settings';
    }

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getFunctions() {
        return array(
            'settings' => new \Twig_Function_Method($this, 'settings')
        );
    }

    public function settings() {
        $user = $this->getUser();

        $settings = $this->getDoctrine()->getRepository('PletnevProjectManagerBundle:Settings')->findOneBy(array('user'=>$user));

        return $settings;
    }

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see Symfony\Component\Security\Core\Authentication\Token\TokenInterface::getUser()
     */
    protected function getUser()
    {
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

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return Registry
     *
     * @throws \LogicException If DoctrineBundle is not available
     */
    protected function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

}
