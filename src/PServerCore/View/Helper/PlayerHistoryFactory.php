<?php


namespace PServerCore\View\Helper;


use PServerCore\Service\PlayerHistory as ServicePlayerHistory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlayerHistoryFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return PlayerHistory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PlayerHistory(
            $serviceLocator->getServiceLocator()->get(ServicePlayerHistory::class),
            $serviceLocator->getServiceLocator()->get('pserver_general_options')
        );
    }

}