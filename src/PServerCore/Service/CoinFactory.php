<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class CoinFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Coin(
            $container->get(EntityManager::class),
            $container->get('gamebackend_dataservice'),
            $container->get('pserver_entity_options'),
            $container->get('pserver_admin_coin_form'),
            $container->get(Ip::class)
        );
    }

}