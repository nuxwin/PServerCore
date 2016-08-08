<?php


namespace PServerCore\Controller;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AccountFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return AccountController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new AccountController(
            $container->get('small_user_service'),
            $container->get('pserver_user_changepwd_form'),
            $container->get('pserver_add_email_service')
        );
    }
}