<?php


namespace PServerCore\Controller;


use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SiteFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|AbstractPluginManager $serviceLocator
     * @return SiteController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new SiteController(
            $serviceLocator->getServiceLocator()->get('pserver_download_service'),
            $serviceLocator->getServiceLocator()->get('pserver_pageinfo_service')
        );
    }

}