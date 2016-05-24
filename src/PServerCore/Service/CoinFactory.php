<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CoinFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Coin
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new Coin(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('gamebackend_dataservice'),
            $serviceLocator->get('pserver_entity_options'),
            $serviceLocator->get('pserver_admin_coin_form'),
            $serviceLocator->get(Ip::class)
        );
    }

}