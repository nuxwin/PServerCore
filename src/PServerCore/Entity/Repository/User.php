<?php

namespace PServerCore\Entity\Repository;

use PServerCore\Entity\UserInterface;

class User extends \SmallUser\Entity\Repository\User
{
    /**
     * @param $username
     *
     * @return null|UserInterface
     */
    public function getUser4UserName($username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    /**
     * @param $username
     *
     * @return bool|null null for user not exists and bool for roles exists or not
     */
    public function isUserValid4UserName($username)
    {
        $result = false;
        $user = $this->getUser4UserName($username);
        if (!$user) {
            $result = null;
        } elseif ($user->getRoles()) {
            $result = true;
        }

        return $result;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUserListQueryBuilder()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.created', 'desc');

        return $query;
    }

}