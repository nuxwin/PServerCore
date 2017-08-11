<?php

namespace PServerCore\Form;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use ZfcTicketSystem\Form\TicketSystem;

class TicketSystemFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TicketSystem
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $form = new TicketSystem(
            $container->get(EntityManager::class),
            $container->get('zfcticketsystem_entry_options')
        );

        $form->setInputFilter(
            new TicketSystemFilter(
                $container->get(EntityManager::class),
                $container->get('zfcticketsystem_entry_options'),
                $container->get('zfc-bbcode_parser')
            )
        );

        return $form;
    }

}