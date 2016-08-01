<?php


namespace PServerCore\Options;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

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

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MailOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, MailOptions::class);
    }

}