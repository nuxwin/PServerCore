<?php


namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DonateFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Donate
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Donate(
            $container->get(EntityManager::class),
            $container->get('pserver_entity_options')
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Donate
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Donate::class);
    }

}