<?php


namespace PServerCore\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CachingHelperFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return CachingHelper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new CachingHelper(
            $serviceLocator->get('pserver_caching_service'),
            $serviceLocator->get('pserver_general_options')
        );
    }

}