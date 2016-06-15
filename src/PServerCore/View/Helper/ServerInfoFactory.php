<?php


namespace PServerCore\View\Helper;


use PServerCore\Service\ServerInfo;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ServerInfoFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return ServerInfoWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ServerInfoWidget(
            $serviceLocator->getServiceLocator()->get(ServerInfo::class)
        );
    }

}