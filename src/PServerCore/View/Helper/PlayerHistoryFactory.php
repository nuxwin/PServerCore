<?php

namespace PServerCore\View\Helper;

use Interop\Container\ContainerInterface;
use PServerCore\Service\PlayerHistory as ServicePlayerHistory;
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
            $container->get(ServicePlayerHistory::class),
            $container->get('pserver_general_options')
        );
    }

}