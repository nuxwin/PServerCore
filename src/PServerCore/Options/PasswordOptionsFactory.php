<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class PasswordOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PasswordOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PasswordOptions($container->get('config')['pserver']['password']);
    }

}