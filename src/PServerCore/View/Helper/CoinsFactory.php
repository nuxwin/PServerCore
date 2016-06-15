<?php


namespace PServerCore\View\Helper;


use PServerCore\Service\Coin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CoinsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return CoinsWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CoinsWidget(
            $serviceLocator->getServiceLocator()->get('small_user_auth_service'),
            $serviceLocator->getServiceLocator()->get(Coin::class)
        );
    }

}