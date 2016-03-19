<?php


namespace PServerCore\View\Helper;

use PServerCore\Service\Coin;
use Zend\Authentication\AuthenticationService;
use Zend\View\Helper\AbstractHelper;

class CoinsWidget extends AbstractHelper
{
    /** @var  AuthenticationService */
    protected $authService;

    /** @var  Coin */
    protected $coinService;

    /**
     * CoinsWidget constructor.
     * @param AuthenticationService $authService
     * @param Coin $coinService
     */
    public function __construct(AuthenticationService $authService, Coin $coinService)
    {
        $this->authService = $authService;
        $this->coinService = $coinService;
    }

    /**
     * @return int
     */
    public function __invoke()
    {
        return $this->coinService->getCoinsOfUser($this->authService->getIdentity());
    }
}