<?php

namespace Pletnev\ProjectManagerBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractSubscriber implements EventSubscriberInterface {

    protected $container;
    protected $sendFrom;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
        if ($this->sendFrom === null) {
            $this->sendFrom = 'pm@pletnev-r.ru';
        }
    }

    /**
     *
     * @return \Symfony\Component\DependencyInjection\Container
     */
    protected function getContainer() {
        return $this->container;
    }

    /**
     * Returns a rendered view.
     *
     * @param string $view       The view name
     * @param array  $parameters An array of parameters to pass to the view
     *
     * @return string The rendered view
     */
    protected function renderView($view, array $parameters = array()) {
        return $this->container->get('templating')->render($view, $parameters);
    }

    /**
     *
     * @param string $subject
     * @param string $body
     * @param string $contentType
     * @param string $charset
     * @return \Swift_Message
     */
    protected function createMessage($subject = null, $body = null, $contentType = 'text/html', $charset = null) {
        return \Swift_Message::newInstance($subject, $body, $contentType, $charset);
    }

    /**
     *
     * @return \Swift_Mailer
     */
    protected function getMailer() {
        return $this->getContainer()->get('mailer');
    }

    protected function getDoctrine(){
        return $this->getContainer()->get('doctrine');
    }

}
