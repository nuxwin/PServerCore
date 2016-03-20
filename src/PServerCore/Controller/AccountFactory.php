<?php


namespace PServerCore\Controller;


use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AccountFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|AbstractPluginManager $serviceLocator
     * @return AccountController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new AccountController(
            $serviceLocator->getServiceLocator()->get('small_user_service'),
            $serviceLocator->getServiceLocator()->get('pserver_user_changepwd_form'),
            $serviceLocator->getServiceLocator()->get('pserver_add_email_service')
        );
    }
}