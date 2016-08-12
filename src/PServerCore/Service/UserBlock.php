<?php

namespace PServerCore\Service;


use DateTime;
use Doctrine\ORM\EntityManager;
use GameBackend\DataService\DataServiceInterface;
use PServerCore\Entity\UserBlock as UserBlockEntity;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\EntityOptions;

class UserBlock
{
    const ERROR_NAME_SPACE = 'p-server-admin-user-panel';

    /** @var  EntityOptions */
    protected $entityOptions;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  DataServiceInterface */
    protected $gameBackendService;

    /**
     * UserBlock constructor.
     * @param EntityOptions $entityOptions
     * @param EntityManager $entityManager
     * @param DataServiceInterface $gameBackendService
     */
    public function __construct(
        EntityOptions $entityOptions,
        EntityManager $entityManager,
        DataServiceInterface $gameBackendService
    ) {
        $this->entityOptions = $entityOptions;
        $this->entityManager = $entityManager;
        $this->gameBackendService = $gameBackendService;
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
        $class = $this->entityOptions->getUserBlock();
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
            new DateTime(),
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
        $entityManager = $this->entityManager;
        /** @var \PServerCore\Entity\Repository\UserBlock $repositoryUserBlock */
        $repositoryUserBlock = $entityManager->getRepository($this->entityOptions->getUserBlock());
        return $repositoryUserBlock->isUserBlocked($user);
    }

    /**
     * @param UserBlockEntity $userBlock
     * @return bool
     */
    public function blockUserWithEntity(UserBlockEntity $userBlock)
    {
        $this->gameBackendService->removeBlockUser($userBlock->getUser());

        $entityManager = $this->entityManager;
        $entityManager->merge($userBlock);
        $entityManager->flush();

        if ($userBlock->getExpire() > new DateTime) {
            $this->gameBackendService->blockUser(
                $userBlock->getUser(),
                $userBlock->getExpire(),
                $userBlock->getReason()
            );
        }
    }

    /**
     * @param $userId
     *
     * @return null|\PServerCore\Entity\UserInterface
     */
    public function getUser4Id($userId)
    {
        /** @var \PServerCore\Entity\Repository\User $userRepository */
        $userRepository = $this->entityManager->getRepository($this->entityOptions->getUser());

        return $userRepository->getUser4Id($userId);
    }
} 