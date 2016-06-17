<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return EntityOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new EntityOptions($serviceLocator->get('config')['pserver']['entity']);
    }

}