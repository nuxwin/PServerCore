<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PaymentNotifyFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PaymentNotify
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PaymentNotify(
            $container->get(EntityManager::class),
            $container->get(Options\Collection::class),
            $container->get(Coin::class),
            $container->get(UserBlock::class)
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PaymentNotify
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, PaymentNotify::class);
    }

}