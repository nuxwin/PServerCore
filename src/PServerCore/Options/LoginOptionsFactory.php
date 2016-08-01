<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LoginOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoginOptions($container->get('config')['pserver']['login']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoginOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, LoginOptions::class);
    }

}