<?php


namespace PServerCore\Service;


use PServerCore\Helper\HelperBasic;
use PServerCore\Helper\HelperService;
use SmallUser\Entity\UserInterface;
use ZfcTicketSystem\Entity\TicketSubject;
use PServerCore\Entity\TicketSystem\TicketSubject as PServerTicketSubject;

class TicketSystem extends \ZfcTicketSystem\Service\TicketSystem
{
    use HelperBasic, HelperService;

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
     * @return \PServerCore\Options\GeneralOptions
     */
    protected function getGeneralOptions()
    {
        return $this->getService('pserver_general_options');
    }

}