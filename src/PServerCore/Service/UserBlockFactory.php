<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserBlockFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserBlock
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new UserBlock(
            $serviceLocator->get('pserver_entity_options'),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('pserver_admin_user_block_form'),
            $serviceLocator->get('gamebackend_dataservice')
        );
    }

}