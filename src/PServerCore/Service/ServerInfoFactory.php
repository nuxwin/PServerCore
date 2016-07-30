<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServerInfoFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ServerInfo
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ServerInfo(
            $container->get(CachingHelper::class),
            $container->get(EntityManager::class),
            $container->get('pserver_entity_options'),
            $container->get('pserver_admin_server_info_form')
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ServerInfo
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, ServerInfo::class);
    }

}