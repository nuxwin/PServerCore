<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use PServerCore\Service\PlayerHistory as ServicePlayerHistory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlayerHistoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return PlayerHistory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new PlayerHistory(
            $container->get(ServicePlayerHistory::class),
            $container->get('pserver_general_options')
        );
    }

    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return PlayerHistory
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), PlayerHistory::class);
    }

}