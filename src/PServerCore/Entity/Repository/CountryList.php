<?php

namespace PServerCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CountryList
 * @package PServerCore\Entity\Repository
 */
class CountryList extends EntityRepository
{

    /**
     * @param int $decimalIp
     *
     * @return \PServerCore\Entity\CountryList
     */
    public function getCountryCode4Ip($decimalIp)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.ipMin <= :ip')
            ->andWhere('p.ipMax >= :ip')
            ->setParameter('ip', $decimalIp)
            ->getQuery();

        /** @var \PServerCore\Entity\CountryList $result */
        $result = $query->getOneOrNullResult();
        if (!$result) {
            return 'ZZZ';
        }
        return $result->getCntry();
    }

    /**
     * @param string $cntry
     *
     * @return string
     */
    public function getDescription4CountryCode($cntry)
    {

        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.cntry = :sCntry')
            ->setParameter('sCntry', $cntry)
            ->setMaxResults(1)
            ->getQuery();

        /** @var \PServerCore\Entity\CountryList $result */
        $result = $query->getOneOrNullResult();

        // no country found
        if (!$result) {
            return 'ZZZ';
        }

        return $result->getCountry();
    }
}