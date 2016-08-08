<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ValidationOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return ValidationOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ValidationOptions($container->get('config')['pserver']['validation']);
    }

}