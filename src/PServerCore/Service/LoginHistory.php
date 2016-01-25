<?php


namespace PServerCore\Service;


use PServerCore\Entity\UserInterface;

class LoginHistory extends InvokableBase
{

    /**
     * @param UserInterface $user
     * @return \PServerCore\Entity\LoginHistory[]
     */
    public function getHistoryList4User(UserInterface $user)
    {
        /** @var \PServerCore\Entity\Repository\LoginHistory $repository */
        $repository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getLoginHistory());
        return $repository->getLastLoginList4User($user);
    }

}