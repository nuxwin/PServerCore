<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return News
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new News(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(Options\Collection::class),
            $serviceLocator->get('pserver_admin_news_form')
        );
    }

}