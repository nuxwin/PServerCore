<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailOptionsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return MailOptions
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new MailOptions($container->get('config')['pserver']['mail']);
    }

}