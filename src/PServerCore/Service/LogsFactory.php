<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LogsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Logs
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new Logs(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('pserver_entity_options')
        );
    }

}