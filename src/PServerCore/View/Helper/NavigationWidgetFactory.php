<?php


namespace PServerCore\View\Helper;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationWidgetFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return NavigationWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new NavigationWidget(
            $serviceLocator->getServiceLocator()->get('config')['pserver']
        );
    }

}