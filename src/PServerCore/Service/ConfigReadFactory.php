<?php


namespace PServerCore\Service;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ConfigReadFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ConfigRead
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ConfigRead(
            $container->get('config')
        );
    }

}