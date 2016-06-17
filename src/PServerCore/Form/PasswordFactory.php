<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use PServerCore\Options\Collection;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PasswordFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Password
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        $form = new Password(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(Collection::class)
        );
        /** @noinspection PhpParamsInspection */
        $form->setInputFilter(
            new PasswordFilter(
                $serviceLocator->get('pserver_password_options'),
                $serviceLocator->get('pserver_secret_question')
            )
        );
        return $form;
    }

}