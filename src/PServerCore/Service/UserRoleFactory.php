<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserRoleFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserRole
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserRole(
            $container->get(EntityManager::class),
            $container->get('pserver_admin_user_role_form'),
            $container->get('pserver_entity_options'),
            $container->get('ControllerPluginManager')
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserRole
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, UserRole::class);
    }

}