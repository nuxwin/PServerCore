<?php


namespace PServerCore\View\Helper;


use PServerCore\Service\News;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NewsFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return NewsWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new NewsWidget(
            $serviceLocator->getServiceLocator()->get(News::class)
        );
    }

}