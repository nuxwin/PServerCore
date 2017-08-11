<?php

namespace PServerCore\Options;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserCodeOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserCodeOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserCodeOptions($container->get('config')['pserver']['user_code']);
    }

}