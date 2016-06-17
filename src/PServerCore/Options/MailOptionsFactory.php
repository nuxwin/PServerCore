<?php


namespace PServerCore\Options;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailOptionsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MailOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MailOptions($serviceLocator->get('config')['pserver']['mail']);
    }

}