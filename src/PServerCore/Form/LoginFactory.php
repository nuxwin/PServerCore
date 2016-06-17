<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use PServerCore\Validator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \SmallUser\Form\Login
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Options\EntityOptions $entityOptions */
        $entityOptions = $serviceLocator->get('pserver_entity_options');
        $repositoryUser = $serviceLocator->get(EntityManager::class)->getRepository($entityOptions->getUser());
        $form = new \SmallUser\Form\Login();
        $form->setInputFilter(
            new LoginFilter(
                new Validator\ValidUserExists($repositoryUser, 'NOT_ACTIVE')
            )
        );

        return $form;
    }

}