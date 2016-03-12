<?php


namespace PServerCore\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use ZfcBBCode\Validator\BBCodeValid;

class TicketSystemFilter extends \ZfcTicketSystem\Form\TicketSystemFilter
{
    /**
     * @param ServiceLocatorInterface $sm
     */
    public function __construct(ServiceLocatorInterface $sm)
    {
        parent::__construct($sm);

        $memo = $this->get('memo');
        $validatorChain = $memo->getValidatorChain();
        $validatorChain->attach(new BBCodeValid($sm->get('zfc-bbcode_parser')));

        $memo->setValidatorChain($validatorChain);
    }
}