<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class ActiveFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Active
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Active(
            $container->get('router'),
            $container->get('request')
        );
    }

}