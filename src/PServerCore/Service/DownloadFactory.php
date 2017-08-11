<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class DownloadFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Download
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Download(
            $container->get(EntityManager::class),
            $container->get('pserver_entity_options'),
            $container->get(CachingHelper::class)
        );
    }

}