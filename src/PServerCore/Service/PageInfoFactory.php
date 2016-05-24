<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PageInfoFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return PageInfo
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new PageInfo(
            $serviceLocator->get(CachingHelper::class),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(Options\Collection::class),
            $serviceLocator->get('pserver_admin_page_info_form')
        );
    }

}