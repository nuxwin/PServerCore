<?php

namespace PServerCore\Service;

use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use PServerCore\Entity\UserCodes as Entity;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\Collection;

class UserCodes
{
    /**
     * @var ObjectRepository
     */
    protected $repositoryManager;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  Format */
    protected $formatService;

    /** @var  Collection */
    protected $collectionOptions;

    /**
     * UserCodes constructor.
     * @param EntityManager $entityManager
     * @param Format $formatService
     * @param Collection $collectionOptions
     */
    public function __construct(EntityManager $entityManager, Format $formatService, Collection $collectionOptions)
    {
        $this->entityManager = $entityManager;
        $this->formatService = $formatService;
        $this->collectionOptions = $collectionOptions;
    }

    /**
     * @param UserInterface $userEntity
     * @param string $type
     * @param int|null $expire
     *
     * @return string
     */
    public function setCode4User(UserInterface $userEntity, $type, $expire = null)
    {
        $entityManager = $this->entityManager;

        $this->getRepositoryManager()->deleteCodes4User($userEntity->getId(), $type);

        do {
            $found = false;
            $code = $this->formatService->getCode();
            if ($this->getRepositoryManager()->getCode($code)) {
                $found = true;
            }
        } while ($found);

        $userCodesEntity = new Entity();
        $userCodesEntity->setCode($code)
            ->setUser($userEntity)
            ->setType($type);

        if (!$expire) {
            $expireOption = $this->collectionOptions->getUserCodesOptions()->getExpire();
            $expire = $expireOption[$type] ?? $expireOption['general'];
        }

        if ($expire) {
            $dateTime = new DateTime();
            $userCodesEntity->setExpire($dateTime->setTimestamp(time() + $expire));
        }

        $entityManager->persist($userCodesEntity);
        $entityManager->flush();

        return $code;
    }

    /**
     * delete a userCode from database
     *
     * @param Entity $userCode
     */
    public function deleteCode(Entity $userCode)
    {
        $entityManager = $this->entityManager;
        $entityManager->remove($userCode);
        $entityManager->flush();
    }

    /**
     * @param int $limit
     *
     * @return int
     */
    public function cleanExpireCodes($limit = 100)
    {
        $codeList = $this->getRepositoryManager()->getExpiredCodes($limit);
        $result = 0;
        if ($codeList) {
            $result = $this->cleanExpireCodes4List($codeList);
        }

        return $result;
    }

    /**
     * @param string $code
     * @param string $type
     * @return null|\PServerCore\Entity\UserCodes
     */
    public function getCode4Data($code, $type)
    {
        /** @var $repositoryCode \PServerCore\Entity\Repository\UserCodes */
        $repositoryCode = $this->entityManager->getRepository(
            $this->collectionOptions->getEntityOptions()->getUserCodes()
        );

        $codeEntity = $repositoryCode->getData4CodeType($code, $type);

        return $codeEntity;
    }

    /**
     * @param Entity[] $codeList
     *
     * @return int
     */
    protected function cleanExpireCodes4List(array $codeList)
    {
        $i = 0;
        $entityManager = $this->entityManager;

        foreach ($codeList as $code) {
            try {
                $entityManager->remove($code);
                // if we have a register-code, so we have to remove the user too
                if ($code->getType() == $code::TYPE_REGISTER) {
                    $user = $code->getUser();
                    /** @var \PServerCore\Entity\Repository\Logs $logRepository */
                    $logRepository = $entityManager->getRepository(
                        $this->collectionOptions->getEntityOptions()->getLogs()
                    );
                    $logRepository->setLogsNull4User($user);

                    /** @var \PServerCore\Entity\Repository\UserExtension $extensionRepository */
                    $extensionRepository = $entityManager->getRepository(
                        $this->collectionOptions->getEntityOptions()->getUserExtension()
                    );
                    $extensionRepository->deleteExtension($user);

                    // secret question
                    if ($this->collectionOptions->getPasswordOptions()->isSecretQuestion()) {
                        /** @var \PServerCore\Entity\Repository\SecretAnswer $answerRepository */
                        $answerRepository = $entityManager->getRepository(
                            $this->collectionOptions->getEntityOptions()->getSecretAnswer()
                        );
                        $answerRepository->deleteAnswer4User($user);
                    }

                    $entityManager->remove($user);
                }
                $entityManager->flush();

                ++$i;
            } catch (\Exception $exception) {
                // skip this exception
            }
        }

        return $i;
    }

    /**
     * @return ObjectRepository|\PServerCore\Entity\Repository\UserCodes
     */
    protected function getRepositoryManager()
    {
        if (!$this->repositoryManager) {
            $this->repositoryManager = $this->entityManager->getRepository(
                $this->collectionOptions->getEntityOptions()->getUserCodes()
            );
        }

        return $this->repositoryManager;
    }

}