<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationWidgetFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return NavigationWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NavigationWidget(
            $container->get('config')['pserver']
        );
    }

    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return NavigationWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), NavigationWidget::class);
    }

}