<?php

namespace PServerCore\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class LoggedInWidget extends AbstractHelper
{
    /** @var  AuthenticationService */
    protected $authService;
    /** @var  array */
    protected $config;

    /**
     * LoggedInWidget constructor.
     * @param AuthenticationService $authService
     * @param array $config
     */
    public function __construct(
        AuthenticationService $authService,
        array $config
    ) {
        $this->authService = $authService;
        $this->config = $config;
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
                'loggedIn' => $this->config['logged_in']
            ]);
            $viewModel->setTemplate('helper/sidebarLoggedInWidget');
            $template = $this->getView()->render($viewModel);
        }

        return $template;
    }
}