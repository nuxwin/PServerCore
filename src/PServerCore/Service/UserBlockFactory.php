<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserBlockFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return UserBlock
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserBlock(
            $container->get('pserver_entity_options'),
            $container->get(EntityManager::class),
            $container->get('pserver_admin_user_block_form'),
            $container->get('gamebackend_dataservice')
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return UserBlock
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, UserBlock::class);
    }

}