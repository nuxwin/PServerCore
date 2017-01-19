<?php

namespace PServerCore\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LoginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LoginWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoginWidget($container->get('small_user_service'));
    }

}