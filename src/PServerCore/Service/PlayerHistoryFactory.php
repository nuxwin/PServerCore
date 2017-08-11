<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options;
use Zend\ServiceManager\Factory\FactoryInterface;

class PlayerHistoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PlayerHistory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlayerHistory(
            $container->get(CachingHelper::class),
            $container->get(EntityManager::class),
            $container->get(Options\Collection::class),
            $container->get('gamebackend_dataservice')
        );
    }

}