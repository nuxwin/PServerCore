<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use PServerCore\Service\Timer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TimerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TimerWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new TimerWidget(
            $container->get('config')['pserver'],
            $container->get(Timer::class)
        );
    }

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