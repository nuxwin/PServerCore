<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PaymentNotifyFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PaymentNotify
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new PaymentNotify(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(Options\Collection::class),
            $serviceLocator->get(Coin::class),
            $serviceLocator->get(UserBlock::class)
        );
    }

}