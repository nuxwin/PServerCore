<?php


namespace PServerCore\Service;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CachingHelperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CachingHelper(
            $container->get('pserver_caching_service'),
            $container->get('pserver_general_options')
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return CachingHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, CachingHelper::class);
    }

}