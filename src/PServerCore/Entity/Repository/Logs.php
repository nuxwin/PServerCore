<?php

namespace PServerCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use PServerCore\Entity\UserInterface;

class Logs extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getLogQueryBuilder()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p', 'user')
            ->leftJoin('p.user', 'user')
            ->orderBy('p.created', 'desc');

        return $query;
    }

    /**
     * @param UserInterface $user
     */
    public function setLogsNull4User(UserInterface $user)
    {
        $query = $this->createQueryBuilder('p')
            ->update()
            ->set('p.user', ':userNull')
            ->setParameter('userNull', null)
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery();

        $query->execute();
    }

}