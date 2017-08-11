<?php

namespace PServerCore\Service;

use Interop\Container\ContainerInterface;
use PServerCore\Form\ChangePwd;
use PServerCore\Options\PasswordOptions;
use Zend\Mvc\Controller\PluginManager;
use Zend\ServiceManager\Factory\FactoryInterface;

class AccountFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Account
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Account(
            $container->get(PasswordOptions::class),
            $container->get(ChangePwd::class),
            $container->get(User::class),
            $container->get('gamebackend_dataservice'),
            $container->get(PluginManager::class)->get('flashMessenger')
        );
    }

}