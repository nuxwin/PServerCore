<?php

namespace PServerCore\Service;

use PServerCore\Entity\TicketSystem\TicketSubject as PServerTicketSubject;
use PServerCore\Options\GeneralOptions;
use SmallUser\Entity\UserInterface;
use ZfcTicketSystem\Entity\TicketSubject;
use ZfcTicketSystem\Service\TicketSystem as ZfcTicketSystem;

class TicketSystem extends ZfcTicketSystem
{
    /** @var  Mail */
    protected $mailService;

    /** @var  GeneralOptions */
    protected $generalOptions;

    /**
     * @param array $data
     * @param UserInterface $user
     * @param TicketSubject|PServerTicketSubject $subject
     * @return bool|\ZfcTicketSystem\Entity\TicketEntry
     */
    public function newAdminEntry(array $data, UserInterface $user, TicketSubject $subject)
    {
        $result = parent::newAdminEntry($data, $user, $subject);

        if (!$result) {
            return $result;
        }

        // check if the config is enabled
        if ($this->getGeneralOptions()->isTicketAnswerMail()) {
            $this->getMailService()->ticketAnswer($subject->getUser(), $subject, $result);
        }

        return $result;
    }

    /**
     * @param string $type
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderSubjectAdminPanel($type)
    {
        /** @var \ZfcTicketSystem\Entity\Repository\TicketSubject $repository */
        $repository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getTicketSubject());

        $queryBuilder = $repository->getQueryBuilder($type);
        $queryBuilder->join('p.user', 'user');
        $queryBuilder->join('p.ticketCategory', 'category');

        return $queryBuilder;
    }

    /**
     * @return Mail
     */
    public function getMailService()
    {
        return $this->mailService;
    }

    /**
     * @param Mail $mailService
     * @return self
     */
    public function setMailService($mailService)
    {
        $this->mailService = $mailService;
        return $this;
    }

    /**
     * @return GeneralOptions
     */
    public function getGeneralOptions()
    {
        return $this->generalOptions;
    }

    /**
     * @param GeneralOptions $generalOptions
     * @return self
     */
    public function setGeneralOptions($generalOptions)
    {
        $this->generalOptions = $generalOptions;
        return $this;
    }


}