<?php


namespace PServerCore\View\Helper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return LoginWidget
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoginWidget($container->get('small_user_service'));
    }

    /**
     * @param ServiceLocatorInterface|ServiceLocatorAwareInterface $serviceLocator
     * @return LoginWidget
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), LoginWidget::class);
    }

}