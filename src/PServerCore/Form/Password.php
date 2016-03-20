<?php

namespace PServerCore\Form;

use Doctrine\ORM\EntityManager;
use PServerCore\Entity\UserInterface;
use PServerCore\Options\Collection;
use Zend\Form\Element;
use ZfcBase\Form\ProvidesEventsForm;

class Password extends ProvidesEventsForm
{
    /** @var  UserInterface */
    protected $user;
    /** @var  EntityManager */
    protected $entityManager;
    /** @var  Collection */
    protected $collectionOptions;

    /**
     * Password constructor.
     * @param EntityManager $entityManager
     * @param Collection $collectionOptions
     */
    public function __construct(EntityManager $entityManager, Collection $collectionOptions)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->collectionOptions = $collectionOptions;

        $this->add([
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'eugzhoe45gh3o49ug2wrtu7gz50'
        ]);

        $this->add([
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'class' => 'form-control',
                'type' => 'password'
            ],
        ]);

        $this->add([
            'name' => 'passwordVerify',
            'options' => [
                'label' => 'Password Verify',
            ],
            'attributes' => [
                'class' => 'form-control',
                'type' => 'password'
            ],
        ]);

        $submitElement = new Element\Button('submit');
        $submitElement
            ->setLabel('Submit')
            ->setAttributes([
                'class' => 'btn btn-default',
                'type' => 'submit',
            ]);

        $this->add($submitElement, [
            'priority' => -100,
        ]);
    }

    /**
     * @param UserInterface $user
     */
    public function addSecretQuestion(UserInterface $user)
    {
        if (!$this->collectionOptions->getPasswordOptions()->isSecretQuestion()) {
            return;
        }

        $this->setUser($user);
        /** @var \PServerCore\Entity\Repository\SecretAnswer $repositorySecretAnswer */
        $repositorySecretAnswer = $this->entityManager
            ->getRepository(
                $this->collectionOptions->getEntityOptions()->getSecretAnswer()
            );
        $answer = $repositorySecretAnswer->getAnswer4UserId($this->getUser()->getId());

        if (!$answer) {
            return;
        }

        $this->add([
            'name' => 'question',
            'options' => [
                'label' => 'SecretQuestion',
            ],
            'attributes' => [
                'placeholder' => 'Question',
                'class' => 'form-control',
                'type' => 'text',
                'disabled' => 'true',
                'value' => $answer->getQuestion()->getQuestion(),
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'answer',
            'options' => [
                'label' => 'SecretAnswer',
            ],
            'attributes' => [
                'placeholder' => 'Answer',
                'class' => 'form-control',
                'type' => 'text',
                'required' => true,
            ],
        ]);

    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }


} 