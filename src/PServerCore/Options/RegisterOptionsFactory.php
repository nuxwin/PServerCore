<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class RegisterOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return RegisterOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RegisterOptions($container->get('config')['pserver']['register']);
    }

}