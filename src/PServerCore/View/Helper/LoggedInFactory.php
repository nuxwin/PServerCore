<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoggedInFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LoggedInWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoggedInWidget(
            $container->get('small_user_auth_service'),
            $container->get('config')['pserver'],
            $container->get('gamebackend_dataservice')
        );
    }

    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return LoggedInWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), LoggedInWidget::class);
    }

}