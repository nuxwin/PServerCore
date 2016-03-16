<?php


namespace PServerCore\Form;


use Doctrine\ORM\EntityManager;
use ZfcBBCode\Service\ParserInterface;
use ZfcBBCode\Validator\BBCodeValid;
use ZfcTicketSystem\Options\EntityOptions;

class TicketSystemFilter extends \ZfcTicketSystem\Form\TicketSystemFilter
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EntityOptions
     */
    protected $entityOptions;

    /**
     * TicketSystemFilter constructor.
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     * @param ParserInterface $parserInterface
     */
    public function __construct(
        EntityManager $entityManager,
        EntityOptions $entityOptions,
        ParserInterface $parserInterface
    ) {
        parent::__construct($entityManager, $entityOptions);

        $memo = $this->get('memo');
        $validatorChain = $memo->getValidatorChain();
        $validatorChain->attach(new BBCodeValid($parserInterface));

        $memo->setValidatorChain($validatorChain);
    }
}