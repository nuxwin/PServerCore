<?php

namespace PServerCore\View\Helper;

use BjyAuthorize\Service\Authorize;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Navigation;

class AccountNavigationWidget extends AbstractHelper
{
    /** @var Authorize */
    protected $authorize;

    /**
     * NavigationWidget constructor.
     * @param Authorize $authorize
     */
    public function __construct(Authorize $authorize)
    {
        $this->authorize = $authorize;
    }

    /**
     * @return Navigation
     */
    public function __invoke()
    {
        /** @var Navigation $navigation */
        $navigation = $this->getView()->navigation();
        $navigation->setAcl($this->authorize->getAcl());
        $navigation->setRole($this->authorize->getIdentity());

        return $navigation;
    }
}