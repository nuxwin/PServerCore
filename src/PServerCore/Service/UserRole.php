<?php


namespace PServerCore\Service;


use PServerCore\Entity\UserInterface;
use Zend\Form\Form;

class UserRole extends InvokableBase
{
    const ErrorNameSpace = 'p-server-admin-user-panel';

    /**
     * @param $data
     * @param $userId
     * @return \PServerCore\Entity\UserInterface
     */
    public function addRoleForm($data, $userId)
    {
        /** @var Form $form */
        $form = $this->getAdminUserRoleForm();
        $form->setData($data);

        if (!$form->isValid()) {
            $this->addFormMessagesInFlashMessenger($form, self::ErrorNameSpace);

            return false;
        }

        $data = $form->getData();
        $roleId = $data['roleId'];

        $user = $this->getUser4Id($userId);

        if ($user) {
            $this->addRole4User($user, $roleId);
        }

        return $user;
    }

    /**
     * @param $userId
     * @param $roleId
     */
    public function removeRole($userId, $roleId)
    {
        $user = $this->getUser4Id($userId);
        $this->removeRole4User($user, $roleId);
    }

    /**
     * @param UserInterface $user
     * @param               $roleId
     * @return bool
     */
    protected function removeRole4User(UserInterface $user, $roleId)
    {
        $role = $this->getRoleEntity4Id($roleId);
        if (!$role) {
            return false;
        }

        $user->removeUserRole($role);
        $role->removeUser($user);
        $this->getEntityManager()->persist($role);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return true;
    }

    /**
     * @param UserInterface $user
     * @param               $roleId
     * @return bool
     */
    protected function addRole4User(UserInterface $user, $roleId)
    {
        $result = false;

        if (!$this->isRoleAlreadyAdded($user, $roleId)) {
            $role = $this->getRoleEntity4Id($roleId);
            $role->addUser($user);
            $user->addUserRole($role);
            $this->getEntityManager()->persist($role);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
            $result = true;
        }

        return $result;
    }

    /**
     * @param $roleId
     * @return null|\PServerCore\Entity\UserRole
     */
    protected function getRoleEntity4Id($roleId)
    {
        /** @var \PServerCore\Entity\Repository\UserRole $repository */
        $repository = $this->getEntityManager()->getRepository($this->getEntityOptions()->getUserRole());
        return $repository->getRole4Id($roleId);
    }

    /**
     * @param UserInterface $user
     * @param               $roleId
     * @return bool
     */
    protected function isRoleAlreadyAdded(UserInterface $user, $roleId)
    {
        $result = false;
        foreach ($user->getRoles() as $role) {
            if ($role->getId() == $roleId) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}