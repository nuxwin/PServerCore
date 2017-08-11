<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PServerCore\Options;
use Zend\ServiceManager\Factory\FactoryInterface;

class MailFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Mail
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Mail(
            $container->get('ViewRenderer'),
            $container->get(Options\Collection::class),
            $container->get(EntityManager::class)
        );
    }

}