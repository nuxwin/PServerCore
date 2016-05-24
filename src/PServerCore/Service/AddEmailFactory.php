<?php


namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use PServerCore\Service;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddEmailFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AddEmail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
        /** @noinspection PhpParamsInspection */
        return new AddEmail(
            $serviceLocator->get('small_user_auth_service'),
            $serviceLocator->get('ControllerPluginManager'),
            $serviceLocator->get(Options\Collection::class),
            $serviceLocator->get('pserver_user_add_mail_form'),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(Service\Mail::class),
            $serviceLocator->get(Service\UserCodes::class)
        );
    }

}