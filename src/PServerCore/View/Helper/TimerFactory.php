<?php

namespace PServerCore\View\Helper;

use Interop\Container\ContainerInterface;
use PServerCore\Service\Timer;
use Zend\ServiceManager\Factory\FactoryInterface;

class TimerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TimerWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TimerWidget(
            $container->get('config')['pserver']['timer'],
            $container->get(Timer::class)
        );
    }

}