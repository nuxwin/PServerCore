<?php

namespace PServerCore\Service;

use PServerCore\Entity\UserCodes as Entity;
use PServerCore\Entity\UserInterface;

class UserCodes extends InvokableBase
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repositoryManager;

    /**
     * @param UserInterface $userEntity
     * @param string $type
     * @param int|null $expire
     *
     * @return string
     */
    public function setCode4User(UserInterface $userEntity, $type, $expire = null)
    {
        $entityManager = $this->getEntityManager();

        $this->getRepositoryManager()->deleteCodes4User($userEntity->getId(), $type);

        do {
            $found = false;
            $code = $this->getFormatService()->getCode();
            if ($this->getRepositoryManager()->getCode($code)) {
                $found = true;
            }
        } while ($found);

        $userCodesEntity = new Entity();
        $userCodesEntity->setCode($code)
            ->setUser($userEntity)
            ->setType($type);

        if (!$expire) {
            $expireOption = $this->getUserCodeOptions()->getExpire();
            if (isset($expireOption[$type])) {
                $expire = $expireOption[$type];
            } else {
                $expire = $expireOption['general'];
            }
        }

        if ($expire) {
            $dateTime = new \DateTime();
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
        $entityManager = $this->getEntityManager();
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
     * @param Entity[] $codeList
     *
     * @return int
     */
    protected function cleanExpireCodes4List(array $codeList)
    {
        $i = 0;
        $entityManager = $this->getEntityManager();

        foreach ($codeList as $code) {
            try {
                $entityManager->remove($code);
                // if we have a register-code, so we have to remove the user too
                if ($code->getType() == $code::TYPE_REGISTER) {
                    $user = $code->getUser();
                    /** @var \PServerCore\Entity\Repository\Logs $logRepository */
                    $logRepository = $entityManager->getRepository($this->getEntityOptions()->getLogs());
                    $logRepository->setLogsNull4User($user);

                    /** @var \PServerCore\Entity\Repository\UserExtension $extensionRepository */
                    $extensionRepository = $entityManager->getRepository($this->getEntityOptions()->getUserExtension());
                    $extensionRepository->deleteExtension($user);

                    // secret question
                    if ($this->getPasswordOptions()->isSecretQuestion()) {
                        /** @var \PServerCore\Entity\Repository\SecretAnswer $answerRepository */
                        $answerRepository = $entityManager->getRepository($this->getEntityOptions()->getSecretAnswer());
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
     * @return \Doctrine\Common\Persistence\ObjectRepository|\PServerCore\Entity\Repository\UserCodes
     */
    protected function getRepositoryManager()
    {
        if (!$this->repositoryManager) {
            $this->repositoryManager = $this->getEntityManager()->getRepository($this->getEntityOptions()->getUserCodes());
        }

        return $this->repositoryManager;
    }

}