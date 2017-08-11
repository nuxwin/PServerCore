<?php

namespace PServerCore\Entity\Repository;

class UserRole extends \SmallUser\Entity\Repository\UserRole
{
    /**
     * @return \PServerCore\Entity\UserRoleInterface[]|null
     */
    public function getRoles()
    {
        return $this->findAll();
    }

    /**
     * @param $id
     * @return \PServerCore\Entity\UserRoleInterface|null
     */
    public function getRole4Id($id)
    {
        return $this->findOneBy(['id' => $id]);
    }
}