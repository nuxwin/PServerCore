<?php

namespace PServerCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime, DateInterval;

use function sprintf;

/**
 * LoginFailed
 */
class LoginFailed extends EntityRepository
{

    /**
     * @param $ip
     * @param $timeInterVal
     *
     * @return int
     */
    public function getNumberOfFailLogin4Ip($ip, $timeInterVal)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.ip = :ipString')
            ->setParameter('ipString', $ip)
            ->andWhere('p.created >= :expireTime')
            ->setParameter('expireTime', (new DateTime())->sub(new DateInterval(sprintf('PT%sS', $timeInterVal))))
            ->getQuery();
        /**
         * TODO remove count
         */
        return count($query->getArrayResult());
    }
}
