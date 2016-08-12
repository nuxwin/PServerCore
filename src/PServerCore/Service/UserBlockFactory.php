<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

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
            $container->get('gamebackend_dataservice')
        );
    }

}