<?php

namespace PServerCore\Options;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class GeneralOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return GeneralOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new GeneralOptions($container->get('config')['pserver']['general']);
    }

}