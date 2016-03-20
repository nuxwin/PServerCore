<?php

namespace PServerCore\Controller;

use PServerCore\Service\User;
use Zend\Mvc\Controller\AbstractActionController;

class DonateController extends AbstractActionController
{
    /** @var  User */
    protected $userService;

    /**
     * DonateController constructor.
     * @param User $userService
     */
    public function __construct(User $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        /** @var \PServerCore\Entity\UserInterface $user */
        $user = $this->userService->getAuthService()->getIdentity();

        return [
            'user' => $user
        ];
    }

}