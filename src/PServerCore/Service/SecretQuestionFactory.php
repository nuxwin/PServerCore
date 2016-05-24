<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SecretQuestionFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SecretQuestion
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        return new SecretQuestion(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('pserver_entity_options'),
            $serviceLocator->get('pserver_admin_secret_question_form')
        );
    }

}