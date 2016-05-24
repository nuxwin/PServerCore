<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServerInfoFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ServerInfo
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new ServerInfo(
            $serviceLocator->get(CachingHelper::class),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('pserver_entity_options'),
            $serviceLocator->get('pserver_admin_server_info_form')
        );
    }

}