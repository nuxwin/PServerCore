<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use PServerCore\Options;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AddEmailFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new AddEmail();
        /** @noinspection PhpParamsInspection */
        $form->setInputFilter(
            new AddEmailFilter(
                $serviceLocator->get(EntityManager::class),
                $serviceLocator->get('config')['pserver'],
                $serviceLocator->get(Options\Collection::class)
            )
        );
        return $form;
    }

}