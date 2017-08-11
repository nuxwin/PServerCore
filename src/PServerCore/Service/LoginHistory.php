<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\EntityOptions;

class LoginHistory
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /**
     * LoginHistory constructor.
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     */
    public function __construct(EntityManager $entityManager, EntityOptions $entityOptions)
    {
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
    }

    /**
     * @param UserInterface $user
     * @return \PServerCore\Entity\LoginHistory[]
     */
    public function getHistoryList4User(UserInterface $user)
    {
        /** @var \PServerCore\Entity\Repository\LoginHistory $repository */
        $repository = $this->entityManager->getRepository($this->entityOptions->getLoginHistory());
        return $repository->getLastLoginList4User($user);
    }

}