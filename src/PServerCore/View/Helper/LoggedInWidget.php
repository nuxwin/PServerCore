<?php

namespace PServerCore\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class LoggedInWidget extends AbstractHelper
{
    /** @var  AuthenticationService */
    protected $authService;

    /**
     * LoggedInWidget constructor.
     * @param AuthenticationService $authService
     */
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $template = '';

        if ($this->authService->hasIdentity()) {
            $user = $this->authService->getIdentity();
            $viewModel = new ViewModel([
                'user' => $user,
            ]);
            $viewModel->setTemplate('helper/sidebarLoggedInWidget');
            $template = $this->getView()->render($viewModel);
        }

        return $template;
    }
}