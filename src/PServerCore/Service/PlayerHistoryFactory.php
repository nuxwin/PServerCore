<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlayerHistoryFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PlayerHistory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new PlayerHistory(
            $serviceLocator->get(CachingHelper::class),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(Options\Collection::class),
            $serviceLocator->get('gamebackend_dataservice')
        );
    }

}