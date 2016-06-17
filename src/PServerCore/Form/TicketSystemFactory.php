<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TicketSystemFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \ZfcTicketSystem\Form\TicketSystem
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @noinspection PhpParamsInspection */
        $form = new \ZfcTicketSystem\Form\TicketSystem(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('zfcticketsystem_entry_options')
        );

        /** @noinspection PhpParamsInspection */
        $form->setInputFilter(
            new TicketSystemFilter(
                $serviceLocator->get(EntityManager::class),
                $serviceLocator->get('zfcticketsystem_entry_options'),
                $serviceLocator->get('zfc-bbcode_parser')
            )
        );

        return $form;
    }

}