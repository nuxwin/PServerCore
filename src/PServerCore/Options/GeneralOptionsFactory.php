<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return GeneralOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, GeneralOptions::class);
    }

}