<?php

namespace PServerCore\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;

class CoinsController extends AbstractActionController
{
    /** @var  AuthenticationService */
    protected $authService;

    /**
     * CoinsController constructor.
     * @param AuthenticationService $authService
     */
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return array
     */
    public function reloadWidgetAction()
    {
        return [
            'user' => $this->authService->getIdentity()
        ];
    }
}