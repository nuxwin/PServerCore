<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\UserInterface;
use PServerCore\Helper\DateTimer;
use PServerCore\Options\EntityOptions;

class Donate
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /**
     * Donate constructor.
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     */
    public function __construct(EntityManager $entityManager, EntityOptions $entityOptions)
    {
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
    }

    /**
     * @param int $lastDays
     *
     * @return array
     */
    public function getStatisticData($lastDays = 10)
    {
        $timestamp = DateTimer::getZeroTimeStamp(strtotime('-' . $lastDays - 1 . ' days'));
        $dateTime = DateTimer::getDateTime4TimeStamp($timestamp);

        $donateEntity = $this->getDonateLogEntity();
        $typList = $donateEntity->getDonateTypes();
        $donateHistory = $donateEntity->getDonateHistorySuccess($dateTime);
        $result = [];

        // set some default values
        $range = DateTimer::getDateRange4Period($dateTime, new \DateTime());
        foreach ($range as $date) {
            foreach ($typList as $type) {
                $result[$date->format('Y-m-d')][$type] = [
                    'amount' => 0,
                    'coins' => 0
                ];
            }
        }

        if ($donateHistory) {
            foreach ($donateHistory as $donateData) {
                /** @var \DateTime $date */
                $date = $donateData['created'];
                $result[$date->format('Y-m-d')][$donateData['type']]['amount'] += $donateData['amount'];
                $result[$date->format('Y-m-d')][$donateData['type']]['coins'] += $donateData['coins'];
            }
        }

        return $result;
    }

    /**
     * @return int
     */
    public function getSumOfDonations()
    {
        $donateData = $this->getDonationDataSuccess();

        return (int)$donateData['coins'];
    }

    /**
     * @return int
     */
    public function getNumberOfDonations()
    {
        $donateData = $this->getDonationDataSuccess();

        return (int)$donateData['amount'];
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getDonateQueryBuilder()
    {
        /** @var \PServerCore\Entity\Repository\DonateLog $repository */
        $repository = $this->entityManager->getRepository($this->entityOptions->getDonateLog());
        return $repository->getDonateQueryBuilder();
    }

    /**
     * @param UserInterface $user
     * @return \PServerCore\Entity\DonateLog[]
     */
    public function getDonateHistory4User(UserInterface $user)
    {
        /** @var \PServerCore\Entity\Repository\DonateLog $repository */
        $repository = $this->entityManager->getRepository($this->entityOptions->getDonateLog());
        return $repository->getDonateHistory4User($user);
    }

    /**
     * @return array
     */
    protected function getDonationDataSuccess()
    {
        $timestamp = DateTimer::getZeroTimeStamp(time());
        $dateTime = DateTimer::getDateTime4TimeStamp($timestamp);

        $donateEntity = $this->getDonateLogEntity();
        $donateData = $donateEntity->getDonationDataSuccess($dateTime);

        return $donateData;
    }

    /**
     * @return \PServerCore\Entity\Repository\DonateLog
     */
    protected function getDonateLogEntity()
    {
        return $this->entityManager->getRepository($this->entityOptions->getDonateLog());
    }

} 