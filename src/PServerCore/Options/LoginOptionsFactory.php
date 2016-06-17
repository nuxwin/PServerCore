<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoginOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LoginOptions($serviceLocator->get('config')['pserver']['login']);
    }

}