<?php


namespace PServerCore\View\Helper;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggedInFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return LoggedInWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LoggedInWidget(
            $serviceLocator->getServiceLocator()->get('small_user_auth_service'),
            $serviceLocator->getServiceLocator()->get('config')['pserver'],
            $serviceLocator->getServiceLocator()->get('gamebackend_dataservice')
        );
    }

}