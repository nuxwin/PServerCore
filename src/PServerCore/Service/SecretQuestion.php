<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\EntityOptions;

class SecretQuestion
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /**
     * SecretQuestion constructor.
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     */
    public function __construct(
        EntityManager $entityManager,
        EntityOptions $entityOptions
    ) {
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
    }

    /**
     * @param UserInterface $user
     * @param $questionId
     * @param $answer
     *
     * @return \PServerCore\Entity\SecretAnswer
     */
    public function setSecretAnswer(UserInterface $user, $questionId, $answer)
    {
        $class = $this->entityOptions->getSecretAnswer();
        /** @var \PServerCore\Entity\SecretAnswer $secretAnswer */
        $secretAnswer = new $class;

        $secretAnswer->setUser($user)
            ->setAnswer(trim($answer))
            ->setQuestion($this->getQuestion4Id($questionId));

        $this->entityManager->persist($secretAnswer);
        $this->entityManager->flush();

        return $secretAnswer;
    }

    /**
     * @param UserInterface $user
     * @param       $answer
     *
     * @return bool
     */
    public function isAnswerAllowed(UserInterface $user, $answer)
    {
        $answerEntity = $this->getEntityManagerAnswer()->getAnswer4UserId($user->getId());

        if (!$answerEntity) {
            return true;
        }

        // @TODO better workflow, with ZendFilter
        $realAnswer = strtolower(trim($answerEntity->getAnswer()));
        $plainAnswer = strtolower(trim($answer));

        return $realAnswer === $plainAnswer;
    }

    /**
     * @param $questionId
     *
     * @return null|\PServerCore\Entity\SecretQuestion
     */
    public function getQuestion4Id($questionId)
    {
        return $this->getQuestionRepository()->getQuestion4Id($questionId);
    }

    /**
     * @return null|\PServerCore\Entity\Repository\SecretAnswer
     */
    protected function getEntityManagerAnswer()
    {
        return $this->entityManager->getRepository($this->entityOptions->getSecretAnswer());
    }

    /**
     * @return \PServerCore\Entity\Repository\SecretQuestion $repository
     */
    protected function getQuestionRepository()
    {
        return $this->entityManager->getRepository($this->entityOptions->getSecretQuestion());
    }

} 