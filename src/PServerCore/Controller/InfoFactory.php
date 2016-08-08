<?php


namespace PServerCore\Controller;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class InfoFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return InfoController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new InfoController(
            $container->get('pserver_playerhistory_service')
        );
    }

}