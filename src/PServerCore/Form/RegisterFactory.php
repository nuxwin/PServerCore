<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use PServerCore\Options\Collection;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Register
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        $form = new Register(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('SanCaptcha'),
            $serviceLocator->get(Collection::class)
        );
        /** @noinspection PhpParamsInspection */
        $form->setInputFilter(
            new RegisterFilter(
                $serviceLocator->get(Collection::class),
                $serviceLocator->get(EntityManager::class),
                $serviceLocator->get('gamebackend_dataservice')
            )
        );
        return $form;
    }

}