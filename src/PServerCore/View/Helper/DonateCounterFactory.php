<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use PServerCore\Service\Donate;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DonateCounterFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return DonateCounter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DonateCounter(
            $container->get(Donate::class)
        );
    }

    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return DonateCounter
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), DonateCounter::class);
    }

}