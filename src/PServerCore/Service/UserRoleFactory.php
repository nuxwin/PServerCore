<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserRoleFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserRole
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new UserRole(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('pserver_admin_user_role_form'),
            $serviceLocator->get('pserver_entity_options'),
            $serviceLocator->get('ControllerPluginManager')
        );
    }

}