<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserCodeOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserCodeOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UserCodeOptions($serviceLocator->get('config')['pserver']['user_code']);
    }

}