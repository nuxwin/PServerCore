<?php

namespace PServerCore\Guard;

use Exception;
use PServerCore\Service\UserBlock as UserBlockService;
use Zend\Authentication\AuthenticationServiceInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class UserBlock extends AbstractListenerAggregate
{
    /**
     * Marker for invalid route errors
     */
    const ERROR = 'error-unauthorized-controller';

    /** @var  AuthenticationServiceInterface */
    protected $authService;

    /** @var  UserBlockService */
    protected $userBlockService;

    /**
     * UserBlock constructor.
     * @param AuthenticationServiceInterface $authService
     * @param UserBlockService $userBlockService
     */
    public function __construct(AuthenticationServiceInterface $authService, UserBlockService $userBlockService)
    {
        $this->authService = $authService;
        $this->userBlockService = $userBlockService;
    }

    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onDispatch'], -2000);
    }

    /**
     * @param MvcEvent $event
     * @return mixed
     */
    public function onDispatch(MvcEvent $event)
    {
        if (!$this->authService->hasIdentity()){
            return null;
        }

        $user = $this->authService->getIdentity();

        if (!$this->userBlockService->isUserBlocked($user)) {
            return null;
        }

        $this->authService->clearIdentity();

        $event->setError(static::ERROR);
        $event->setParam('exception', new Exception('You are not authorized'));

        /* @var $app \Zend\Mvc\ApplicationInterface */
        $app = $event->getTarget();
        $eventManager = $app->getEventManager();
        $eventManager->setEventPrototype($event);

        $results = $eventManager->trigger(
            MvcEvent::EVENT_DISPATCH_ERROR,
            null,
            $event->getParams()
        );
        $return  = $results->last();
        if (! $return) {
            return $event->getResult();
        }

        return $return;
    }
}