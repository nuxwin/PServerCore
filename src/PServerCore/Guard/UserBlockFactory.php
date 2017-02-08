<?php

namespace PServerCore\Guard;

use Interop\Container\ContainerInterface;
use PServerCore\Service\UserBlock as UserBlockService;
use SmallUser\Service\UserAuthFactory;
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
            $container->get(UserAuthFactory::class),
            $container->get(UserBlockService::class)
        );
    }

}