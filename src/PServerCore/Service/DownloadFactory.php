<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DownloadFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Download
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new Download(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('pserver_entity_options'),
            $serviceLocator->get(CachingHelper::class),
            $serviceLocator->get('pserver_admin_download_form')
        );
    }

}