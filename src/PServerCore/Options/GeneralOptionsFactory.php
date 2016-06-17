<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GeneralOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return GeneralOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new GeneralOptions($serviceLocator->get('config')['pserver']['general']);
    }

}