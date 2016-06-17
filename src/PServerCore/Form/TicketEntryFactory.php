<?php


namespace PServerCore\Form;


use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TicketEntryFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \ZfcTicketSystem\Form\TicketEntry
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new \ZfcTicketSystem\Form\TicketEntry();
        /** @noinspection PhpParamsInspection */
        $form->setInputFilter(new TicketEntryFilter($serviceLocator->get('zfc-bbcode_parser')));

        return $form;
    }

}