<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return EntityOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new EntityOptions($container->get('config')['pserver']['entity']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return EntityOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, EntityOptions::class);
    }

}