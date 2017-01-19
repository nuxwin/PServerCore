<?php

namespace PServerCore\Controller;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CoinsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return CoinsController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CoinsController($container->get('small_user_auth_service'));
    }

}