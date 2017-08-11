<?php

namespace PServerCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class PlayerHistory extends EntityRepository
{
    /**
     * @return integer
     */
    public function getCurrentPlayer()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.created', 'desc')
            ->setMaxResults(1)
            ->getQuery();

        /** @var \PServerCore\Entity\PlayerHistory $result */
        $result = $query->getOneOrNullResult();

        return $result === null ? 0 : $result->getPlayer();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->createQueryBuilder('p')
            ->select('p');
    }
} 