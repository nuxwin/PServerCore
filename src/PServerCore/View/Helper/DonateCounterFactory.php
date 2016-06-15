<?php


namespace PServerCore\View\Helper;


use PServerCore\Service\Donate;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DonateCounterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return DonateCounter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DonateCounter(
            $serviceLocator->getServiceLocator()->get(Donate::class)
        );
    }

}