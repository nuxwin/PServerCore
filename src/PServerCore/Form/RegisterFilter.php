<?php

namespace PServerCore\Form;

use Doctrine\ORM\EntityManager;
use GameBackend\DataService\DataServiceInterface;
use PServerCore\Options\Collection;
use PServerCore\Validator;
use PServerCore\Validator\AbstractRecord;
use Zend\Filter;
use Zend\I18n\Validator\Alnum;
use Zend\InputFilter\InputFilter;
use Zend\Validator as ZendValidator;

class RegisterFilter extends InputFilter
{
    /** @var  Collection */
    protected $collection;

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  DataServiceInterface */
    protected $gameBackendService;

    /**
     * RegisterFilter constructor.
     * @param Collection $collection
     * @param EntityManager $entityManager
     * @param DataServiceInterface $gameBackendService
     */
    public function __construct(
        Collection $collection,
        EntityManager $entityManager,
        DataServiceInterface $gameBackendService
    ) {
        $this->collection = $collection;
        $this->entityManager = $entityManager;
        $this->gameBackendService = $gameBackendService;

        $validationUsernameOptions = $this->collection->getValidationOptions()->getUsername();

        $this->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => ZendValidator\StringLength::class,
                    'options' => [
                        'min' => $validationUsernameOptions['length']['min'],
                        'max' => $validationUsernameOptions['length']['max'],
                    ],
                ],
                [
                    'name' => Alnum::class,
                ],
                $this->getUsernameValidator(),
                $this->getUserNameBackendNotExistsValidator()
            ],
        ]);

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => ZendValidator\EmailAddress::class,
                    'options' => [
                        'allow' => ZendValidator\Hostname::ALLOW_DNS,
                        'useMxCheck' => true,
                        'useDeepMxCheck' => true
                    ]
                ],
                $this->getStriposValidator()
            ],
        ]);

        if (!$this->collection->getRegisterOptions()->isDuplicateEmail()) {
            $element = $this->get('email');
            /** @var \Zend\Validator\ValidatorChain $chain */
            $chain = $element->getValidatorChain();
            $chain->attach($this->getEmailValidator());
            $element->setValidatorChain($chain);
        }

        $this->add([
            'name' => 'emailVerify',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => ZendValidator\StringLength::class,
                    'options' => [
                        'min' => 6,
                        'max' => 255,
                    ],
                ],
                [
                    'name' => ZendValidator\Identical::class,
                    'options' => [
                        'token' => 'email',
                    ],
                ],
            ],
        ]);

        $passwordLengthOptions = $this->collection->getPasswordOptions()->getLength();

        $this->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => ZendValidator\StringLength::class,
                    'options' => [
                        'min' => $passwordLengthOptions['min'],
                        'max' => $passwordLengthOptions['max'],
                    ],
                ],
                new Validator\PasswordRules($this->collection->getPasswordOptions()),
            ],
        ]);

        $this->add([
            'name' => 'passwordVerify',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => ZendValidator\StringLength::class,
                    'options' => [
                        'min' => $passwordLengthOptions['min'],
                        'max' => $passwordLengthOptions['max'],
                    ],
                ],
                [
                    'name' => ZendValidator\Identical::class,
                    'options' => [
                        'token' => 'password',
                    ],
                ],
                new Validator\PasswordRules($this->collection->getPasswordOptions()),
            ],
        ]);

        if ($this->collection->getPasswordOptions()->isSecretQuestion()) {
            $this->add([
                'name' => 'question',
                'required' => true,
                'validators' => [
                    [
                        'name' => ZendValidator\InArray::class,
                        'options' => [
                            'haystack' => $this->getSecretQuestionList(),
                        ],
                    ],
                ],
            ]);
            $this->add([
                'name' => 'answer',
                'required' => true,
                'filters' => [
                    ['name' => Filter\StringTrim::class],
                ],
                'validators' => [
                    [
                        'name' => ZendValidator\StringLength::class,
                        'options' => [
                            'min' => 3,
                            'max' => 255,
                        ],
                    ],
                ],
            ]);
        }
    }

    /**
     * @return array
     */
    protected function getSecretQuestionList()
    {
        /** @var \PServerCore\Entity\Repository\SecretQuestion $secret */
        $secret = $this->entityManager->getRepository($this->collection->getEntityOptions()->getSecretQuestion());
        $secretQuestion = $secret->getQuestions();

        $result = [];
        foreach ($secretQuestion as $entry) {
            $result[] = $entry->getId();
        }

        return $result;
    }

    /**
     * @return AbstractRecord
     */
    public function getUsernameValidator()
    {
        /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
        $repositoryUser = $this->entityManager->getRepository($this->collection->getEntityOptions()->getUser());

        return new Validator\NoRecordExists($repositoryUser, 'username');
    }

    /**
     * @return AbstractRecord
     */
    public function getEmailValidator()
    {
        /** @var $repositoryUser \Doctrine\Common\Persistence\ObjectRepository */
        $repositoryUser = $this->entityManager->getRepository($this->collection->getEntityOptions()->getUser());

        return new Validator\NoRecordExists($repositoryUser, 'email');
    }

    /**
     * @return Validator\StriposExists
     */
    public function getStriposValidator()
    {
        return new Validator\StriposExists($this->collection->getConfig(), Validator\StriposExists::TYPE_EMAIL);
    }

    /**
     * @return Validator\UserNameBackendNotExists
     */
    public function getUserNameBackendNotExistsValidator()
    {
        return new Validator\UserNameBackendNotExists(
            $this->gameBackendService,
            $this->collection->getEntityOptions()
        );
    }


} 