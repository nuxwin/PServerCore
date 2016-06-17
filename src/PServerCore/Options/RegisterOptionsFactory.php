<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RegisterOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RegisterOptions($serviceLocator->get('config')['pserver']['register']);
    }

}