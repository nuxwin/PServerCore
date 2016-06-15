<?php


namespace PServerCore\View\Helper;


use PServerCore\Service\Donate;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DonateSumFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return DonateSum
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DonateSum(
            $serviceLocator->getServiceLocator()->get(Donate::class)
        );
    }

}