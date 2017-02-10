<?php

namespace PServerCore\Guard;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\Repository\User;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\EntityOptions;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class UserRefresh extends AbstractListenerAggregate
{
    /** @var  AuthenticationService */
    protected $authService;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOption;

    /**
     * UserRefresh constructor.
     * @param AuthenticationService $authService
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOption
     */
    public function __construct(
        AuthenticationService $authService,
        EntityManager $entityManager,
        EntityOptions $entityOption
    ) {
        $this->authService = $authService;
        $this->entityManager = $entityManager;
        $this->entityOption = $entityOption;
    }

    /**
     * @inheritDoc
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'onDispatch'], -3000);
    }

    /**
     * @param MvcEvent $event
     */
    public function onDispatch(MvcEvent $event)
    {
        if (!$this->authService->hasIdentity()){
            return;
        }

        /** @var UserInterface $user */
        $user = $this->authService->getIdentity();

        /** @var User $userRepository */
        $userRepository = $this->entityManager->getRepository($this->entityOption->getUser());

        // fix if we have a proxy we don´t have a valid entity, so we have to clear before we can create a new select
        $username = $user->getUsername();
        $userRepository->clear();

        $user = $userRepository->getUser4UserName($username);

        $this->authService->getStorage()->write($user);
    }
}