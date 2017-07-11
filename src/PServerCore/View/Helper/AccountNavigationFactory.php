<?php

namespace PServerCore\View\Helper;

use BjyAuthorize\Service\Authorize;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AccountNavigationFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AccountNavigationWidget(
            $container->get(Authorize::class)
        );
    }

}