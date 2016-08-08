<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use PServerCore\Service\ServerInfo;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class ServerInfoFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ServerInfoWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ServerInfoWidget(
            $container->get(ServerInfo::class)
        );
    }

}