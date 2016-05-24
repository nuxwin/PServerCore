<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginHistoryFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return LoginHistory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new LoginHistory(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('pserver_entity_options')
        );
    }

}