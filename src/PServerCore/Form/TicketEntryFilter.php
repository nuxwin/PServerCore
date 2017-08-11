<?php

namespace PServerCore\Form;

use ZfcBBCode\Service\ParserInterface;
use ZfcBBCode\Validator\BBCodeValid;
use ZfcTicketSystem\Form\TicketEntryFilter as ZfcTicketEntryFilter;

class TicketEntryFilter extends ZfcTicketEntryFilter
{
    /**
     * @param ParserInterface $parserInterface
     */
    public function __construct(ParserInterface $parserInterface)
    {
        parent::__construct();

        $memo = $this->get('memo');
        $validatorChain = $memo->getValidatorChain();
        $validatorChain->attach(new BBCodeValid($parserInterface));

        $memo->setValidatorChain($validatorChain);
    }
}