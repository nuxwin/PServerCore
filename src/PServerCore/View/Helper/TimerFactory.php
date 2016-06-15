<?php


namespace PServerCore\View\Helper;


use PServerCore\Service\Timer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TimerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return TimerWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TimerWidget(
            $serviceLocator->getServiceLocator()->get('config')['pserver'],
            $serviceLocator->getServiceLocator()->get(Timer::class)
        );
    }

}