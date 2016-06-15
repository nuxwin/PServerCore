<?php


namespace PServerCore\View\Helper;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return LoginWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LoginWidget($serviceLocator->getServiceLocator()->get('small_user_service'));
    }

}