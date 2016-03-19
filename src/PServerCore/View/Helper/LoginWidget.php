<?php


namespace PServerCore\View\Helper;

use PServerCore\Service\User;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class LoginWidget extends AbstractHelper
{
    /** @var  User */
    protected $userService;

    /**
     * LoginWidget constructor.
     * @param User $userService
     */
    public function __construct(User $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $template = '';

        if (!$this->userService->getAuthService()->hasIdentity()) {
            $viewModel = new ViewModel([
                'loginForm' => $this->userService->getLoginForm(),
            ]);
            $viewModel->setTemplate('helper/sidebarLoginWidget');
            $template = $this->getView()->render($viewModel);
        }

        return $template;
    }

}