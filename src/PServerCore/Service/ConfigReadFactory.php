<?php


namespace PServerCore\Service;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfigReadFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ConfigRead
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ConfigRead(
            $serviceLocator->get('Config')
        );
    }

}