<?php


namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use PServerCore\Options;
use PServerCore\Service;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddEmailFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AddEmail
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
        return new AddEmail(
            $container->get('small_user_auth_service'),
            $container->get('ControllerPluginManager'),
            $container->get(Options\Collection::class),
            $container->get('pserver_user_add_mail_form'),
            $container->get(EntityManager::class),
            $container->get(Service\Mail::class),
            $container->get(Service\UserCodes::class)
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return AddEmail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $sm \Zend\ServiceManager\ServiceLocatorInterface */
        return $this($serviceLocator, AddEmail::class);
    }

}