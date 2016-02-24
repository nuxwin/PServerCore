<?php

namespace PServerCore\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use PServerCore\Entity\UserInterface;

/**
 * IPBlock
 */
class UserBlock extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @param null|\DateTime $expireTime
     * @return null|\PServerCore\Entity\UserBlock
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isUserBlocked(UserInterface $user, $expireTime = null)
    {
        if (!$expireTime) {
            $expireTime = new \DateTime();
        }

        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.id', 'desc')
            ->setMaxResults(1)
            ->getQuery();

        /** @var null|\PServerCore\Entity\UserBlock $blockEntity */
        $blockEntity = $query->getOneOrNullResult();

        $result = null;
        if ($blockEntity && $blockEntity->getExpire() > $expireTime) {
            $result = $blockEntity;
        }

        return $result;
    }
}