<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Mail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new Mail(
            $serviceLocator->get('ViewRenderer'),
            $serviceLocator->get(Options\Collection::class),
            $serviceLocator->get(EntityManager::class)
        );
    }

}