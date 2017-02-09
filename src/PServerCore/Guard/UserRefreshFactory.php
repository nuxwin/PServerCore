<?php

namespace PServerCore\Guard;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options\EntityOptions;
use SmallUser\Service\UserAuthFactory;
use Zend\ServiceManager\Factory\FactoryInterface;

class UserRefreshFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserRefresh(
            $container->get(UserAuthFactory::class),
            $container->get(EntityManager::class),
            $container->get(EntityOptions::class)
        );
    }


}