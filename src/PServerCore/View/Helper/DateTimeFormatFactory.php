<?php


namespace PServerCore\View\Helper;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DateTimeFormatFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return DateTimeFormat
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DateTimeFormat(
            $serviceLocator->getServiceLocator()->get('pserver_general_options')
        );
    }

}