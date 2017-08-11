<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options;
use Zend\ServiceManager\Factory\FactoryInterface;

class NewsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return News
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new News(
            $container->get(EntityManager::class),
            $container->get(Options\Collection::class)
        );
    }

}