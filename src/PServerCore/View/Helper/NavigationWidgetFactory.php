<?php

namespace PServerCore\View\Helper;

use BjyAuthorize\Service\Authorize;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class NavigationWidgetFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NavigationWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NavigationWidget(
            $container->get(Authorize::class)
        );
    }

}