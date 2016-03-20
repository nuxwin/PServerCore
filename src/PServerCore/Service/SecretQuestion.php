<?php

namespace PServerCore\Service;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\SecretQuestion as Entity;
use PServerCore\Entity\UserInterface;
use PServerCore\Mapper\HydratorSecretQuestion;
use PServerCore\Options\EntityOptions;
use Zend\Form\FormInterface;

class SecretQuestion
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  EntityOptions */
    protected $entityOptions;

    /** @var  FormInterface */
    protected $adminSecretQuestionForm;

    /**
     * SecretQuestion constructor.
     * @param EntityManager $entityManager
     * @param EntityOptions $entityOptions
     * @param FormInterface $adminSecretQuestionForm
     */
    public function __construct(
        EntityManager $entityManager,
        EntityOptions $entityOptions,
        FormInterface $adminSecretQuestionForm
    ) {
        $this->entityManager = $entityManager;
        $this->entityOptions = $entityOptions;
        $this->adminSecretQuestionForm = $adminSecretQuestionForm;
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
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQuestionQueryBuilder()
    {
        return $this->getQuestionRepository()->getQuestionQueryBuilder();
    }

    /**
     * @param array $data
     * @param null|Entity $currentSecretQuestion
     *
     * @return bool|Entity
     */
    public function secretQuestion(array $data, $currentSecretQuestion = null)
    {
        if ($currentSecretQuestion == null) {
            $class = $this->entityOptions->getSecretQuestion();
            /** @var Entity $currentSecretQuestion */
            $currentSecretQuestion = new $class;
        }

        $form = $this->adminSecretQuestionForm;
        $form->setData($data);
        $form->setHydrator(new HydratorSecretQuestion());
        $form->bind($currentSecretQuestion);
        if (!$form->isValid()) {
            return false;
        }

        /** @var Entity $secretQuestion */
        $secretQuestion = $form->getData();

        $this->entityManager->persist($secretQuestion);
        $this->entityManager->flush();

        return $secretQuestion;
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
     * @return FormInterface
     */
    public function getAdminSecretQuestionForm()
    {
        return $this->adminSecretQuestionForm;
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