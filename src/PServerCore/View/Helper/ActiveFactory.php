<?php


namespace PServerCore\View\Helper;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ActiveFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return Active
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Active(
            $serviceLocator->getServiceLocator()->get('router'),
            $serviceLocator->getServiceLocator()->get('request')
        );
    }

}