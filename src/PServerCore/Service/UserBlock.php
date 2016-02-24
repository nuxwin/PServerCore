<?php

namespace PServerCore\Service;


use PServerCore\Mapper\HydratorUserBlock;
use PServerCore\Entity\Userblock as UserBlockEntity;
use PServerCore\Entity\UserInterface;

class UserBlock extends InvokableBase
{
    const ErrorNameSpace = 'p-server-admin-user-panel';

    /**
     * @param $data
     * @param $userId
     * @return \PServerCore\Entity\UserInterface
     */
    public function blockForm($data, $userId, $creator = null)
    {
        $class = $this->getEntityOptions()->getUserBlock();
        /** @var UserBlockEntity $userBlock */

        $form = $this->getAdminUserBlockForm();
        $form->setHydrator(new HydratorUserBlock());
        $form->bind(new $class);
        $form->setData($data);

        if (!$form->isValid()) {
            return false;
        }
        /** @var UserBlockEntity $userBlockEntity */
        $userBlockEntity = $form->getData();
        $user = $this->getUser4Id($userId);

        if ($user) {
            $userBlockEntity->setUser($user);
            $userBlockEntity->setCreator($creator);
            $this->blockUserWithEntity($userBlockEntity);
        }

        return $user;
    }

    /**
     * We want to block a user
     *
     * @param UserInterface $user
     * @param $expire
     * @param $reason
     * @param null|UserInterface $creator
     */
    public function blockUser(UserInterface $user, $expire, $reason, $creator = null)
    {
        $class = $this->getEntityOptions()->getUserBlock();
        /** @var UserBlockEntity $userBlock */
        $userBlock = new $class;
        $userBlock->setUser($user);
        $userBlock->setCreator($creator);
        $userBlock->setReason($reason);
        $userBlock->setExpire($expire);

        $this->blockUserWithEntity($userBlock);
    }

    /**
     * @param UserInterface|int $user
     * @param null|UserInterface $creator
     * @return bool
     */
    public function removeBlock($user, $creator = null)
    {
        if (!$user instanceof UserInterface) {
            $user = $this->getUser4Id($user);

            if (!$user) {
                return false;
            }
        }

        $this->blockUser(
            $user,
            new \DateTime(),
            '',
            $creator
        );

        return true;
    }

    /**
     * @param UserInterface $user
     * @return null|\PServerCore\Entity\UserBlock
     */
    public function isUserBlocked(UserInterface $user)
    {
        $entityManager = $this->getEntityManager();
        /** @var \PServerCore\Entity\Repository\UserBlock $repositoryUserBlock */
        $repositoryUserBlock = $entityManager->getRepository($this->getEntityOptions()->getUserBlock());
        return $repositoryUserBlock->isUserBlocked($user);
    }

    /**
     * @param UserBlockEntity $userBlock
     * @return bool
     */
    protected function blockUserWithEntity(UserBlockEntity $userBlock)
    {
        $this->getGameBackendService()->removeBlockUser($userBlock->getUser());

        $entityManager = $this->getEntityManager();
        $entityManager->merge($userBlock);
        $entityManager->flush();

        if ($userBlock->getExpire() > new \DateTime) {
            $this->getGameBackendService()->blockUser(
                $userBlock->getUser(),
                $userBlock->getExpire(),
                $userBlock->getReason()
            );
        }
    }
} 