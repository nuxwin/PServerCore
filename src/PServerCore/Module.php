<?php

namespace PServerCore;

use PServerCore\Service\ServiceManager;
use Zend\EventManager\EventInterface;

class Module
{
    /**
     * @param EventInterface $event
     */
    public function onBootstrap(EventInterface $event)
    {
        /* @var $app \Zend\Mvc\ApplicationInterface */
        $app = $event->getTarget();
        $eventManager = $app->getEventManager();
        $guardList = $app->getServiceManager()->get('config')['pserver']['guard'];

        foreach ($guardList as $guard) {
            $app->getServiceManager()->get($guard)->attach($eventManager);
        }

        ServiceManager::setInstance($app->getServiceManager());
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

}
