<?php


namespace PServerCore\View\Helper;

use GameBackend\DataService\DataServiceInterface;
use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class LoggedInWidget extends AbstractHelper
{
    /** @var  AuthenticationService */
    protected $authService;
    /** @var  array */
    protected $config;
    /** @var  DataServiceInterface */
    protected $gameBackendService;

    /**
     * LoggedInWidget constructor.
     * @param AuthenticationService $authService
     * @param array $config
     * @param DataServiceInterface $gameBackendService
     */
    public function __construct(
        AuthenticationService $authService,
        array $config,
        DataServiceInterface $gameBackendService
    ) {
        $this->authService = $authService;
        $this->config = $config;
        $this->gameBackendService = $gameBackendService;
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
                'coins' => $this->gameBackendService->getCoins($user),
                'loggedIn' => $this->config['logged_in']
            ]);
            $viewModel->setTemplate('helper/sidebarLoggedInWidget');
            $template = $this->getView()->render($viewModel);
        }

        return $template;
    }
}