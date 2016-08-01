<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ValidationOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ValidationOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ValidationOptions($container->get('config')['pserver']['validation']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ValidationOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, ValidationOptions::class);
    }

}