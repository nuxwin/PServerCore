<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SecretQuestionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return SecretQuestion
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new SecretQuestion(
            $container->get(EntityManager::class),
            $container->get('pserver_entity_options'),
            $container->get('pserver_admin_secret_question_form')
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return SecretQuestion
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, SecretQuestion::class);
    }

}