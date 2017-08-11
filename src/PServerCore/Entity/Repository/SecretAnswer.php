<?php

namespace PServerCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use PServerCore\Entity\UserInterface;

class SecretAnswer extends EntityRepository
{
    /**
     * @param int $userId
     * @return null|\PServerCore\Entity\SecretAnswer
     */
    public function getAnswer4UserId($userId)
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.user = :user')
            ->setParameter('user', $userId)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param UserInterface $user
     */
    public function deleteAnswer4User($user)
    {
        $this->createQueryBuilder('p')
            ->delete()
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }
} 