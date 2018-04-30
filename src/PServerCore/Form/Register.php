<?php

namespace PServerCore\Form;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Form\Element\ObjectSelect;
use PServerCore\Options\Collection;
use Zend\Captcha\AdapterInterface;
use Zend\Form;

class Register extends Form\Form
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  AdapterInterface */
    protected $sanCaptcha;

    /** @var  Collection */
    protected $collection;

    /**
     * Register constructor.
     * @param EntityManager $entityManager
     * @param AdapterInterface $sanCaptcha
     * @param Collection $collection
     */
    public function __construct(EntityManager $entityManager, AdapterInterface $sanCaptcha, Collection $collection)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->sanCaptcha = $sanCaptcha;
        $this->collection = $collection;

        $this->add([
            'type' => Form\Element\Csrf::class,
            'name' => 'eugzhoe45gh3o49ug2wrtu7gz50'
        ]);

        $this->add([
            'name' => 'username',
            'options' => [
                'label' => 'Username',
            ],
            'attributes' => [
                'placeholder' => 'Username',
                'class' => 'form-control',
                'type' => 'text',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'placeholder' => 'Email',
                'class' => 'form-control',
                'type' => 'email',
                'required' => true,
            ],
        ]);
        $this->add([
            'name' => 'emailVerify',
            'options' => [
                'label' => 'Email Verify',
            ],
            'attributes' => [
                'placeholder' => 'Email Verify',
                'class' => 'form-control',
                'type' => 'email',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'placeholder' => 'Password',
                'class' => 'form-control',
                'type' => 'password',
                'required' => true,
            ],
        ]);

        $this->add([
            'name' => 'passwordVerify',
            'options' => [
                'label' => 'Password Verify',
            ],
            'attributes' => [
                'placeholder' => 'Password Verify',
                'class' => 'form-control',
                'type' => 'password',
                'required' => true,
            ],
        ]);

        if ($this->collection->getConfig()['password']['secret_question']) {
            $entityOptions = $this->collection->getEntityOptions();

            $this->add([
                'name' => 'question',
                'type' => ObjectSelect::class,
                'options' => [
                    'object_manager' => $this->entityManager,
                    'target_class' => $entityOptions->getSecretQuestion(),
                    'property' => 'question',
                    'label' => 'SecretQuestion',
                    'empty_option' => '-- select --',
                    'is_method' => true,
                    'find_method' => [
                        'name' => 'getQuestions',
                    ],
                ],
                'attributes' => [
                    'class' => 'form-control',
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

        $captcha = new Form\Element\Captcha('captcha');
        $captcha->setCaptcha($this->sanCaptcha)
            ->setOptions([
                'label' => 'Please verify you are human.',
            ])
            ->setAttributes([
                'class' => 'form-control',
                'type' => 'text',
                'required' => true,
                'autocomplete' => 'off',
            ]);

        $this->add($captcha, [
            'priority' => -90,
        ]);

        $submitElement = new Form\Element\Button('submit');
        $submitElement
            ->setLabel('Register')
            ->setAttributes([
                'class' => 'btn btn-secondary',
                'type' => 'submit',
            ]);

        $this->add($submitElement, [
            'priority' => -100,
        ]);

    }
}