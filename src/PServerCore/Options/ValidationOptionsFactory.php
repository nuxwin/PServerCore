<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ValidationOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ValidationOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ValidationOptions($serviceLocator->get('config')['pserver']['validation']);
    }

}