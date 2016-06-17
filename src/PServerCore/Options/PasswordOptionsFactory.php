<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PasswordOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PasswordOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PasswordOptions($serviceLocator->get('config')['pserver']['password']);
    }

}