<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class LogsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Logs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Logs(
            $container->get(EntityManager::class),
            $container->get('pserver_entity_options')
        );
    }

}