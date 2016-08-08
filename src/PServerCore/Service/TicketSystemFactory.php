<?php


namespace PServerCore\Service;


use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

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
        $ticketSystem = new TicketSystem(
            $container->get(EntityManager::class),
            $container->get('zfcticketsystem_ticketsystem_new_form'),
            $container->get('zfcticketsystem_ticketsystem_entry_form'),
            $container->get('zfcticketsystem_entry_options')
        );

        /** @noinspection PhpParamsInspection */
        $ticketSystem->setMailService($container->get(Mail::class));
        /** @noinspection PhpParamsInspection */
        $ticketSystem->setGeneralOptions($container->get('pserver_general_options'));

        return $ticketSystem;
    }

}