<?php

namespace PServerCore\Form;

use PServerCore\Entity\UserInterface;
use PServerCore\Options\PasswordOptions;
use PServerCore\Service\SecretQuestion;
use PServerCore\Validator\PasswordRules;
use PServerCore\Validator\SimilarText;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class PasswordFilter extends InputFilter
{
    /** @var  SimilarText */
    protected $similarText;

    /** @var  UserInterface */
    protected $user;

    /** @var  PasswordOptions */
    protected $passwordOptions;

    /**
     * PasswordFilter constructor.
     * @param PasswordOptions $passwordOptions
     * @param SecretQuestion $secretQuestionService
     */
    public function __construct(PasswordOptions $passwordOptions, SecretQuestion $secretQuestionService)
    {
        $this->passwordOptions = $passwordOptions;

        if ($this->passwordOptions->isSecretQuestion()) {
            $similarText = new SimilarText($secretQuestionService);
            $this->setSimilarText($similarText);
        }

        $passwordLengthOptions = $this->passwordOptions->getLength();

        $this->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => $passwordLengthOptions['min'],
                        'max' => $passwordLengthOptions['max'],
                    ],
                ],
                new PasswordRules($passwordOptions),
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
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => $passwordLengthOptions['min'],
                        'max' => $passwordLengthOptions['max'],
                    ],
                ],
                [
                    'name' => Validator\Identical::class,
                    'options' => [
                        'token' => 'password',
                    ],
                ],
                new PasswordRules($passwordOptions),
            ],
        ]);
    }

    /**
     * @param UserInterface $user
     */
    public function addAnswerValidation(UserInterface $user)
    {
        $similarText = $this->getSimilarText();
        if (!$similarText) {
            return;
        }

        $similarText->setUser($user);

        $this->add([
            'name' => 'answer',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                $similarText,
            ],
        ]);

    }

    /**
     * @param SimilarText $similarText
     */
    protected function setSimilarText(SimilarText $similarText)
    {
        $this->similarText = $similarText;
    }

    /**
     * @return SimilarText
     */
    protected function getSimilarText()
    {
        return $this->similarText;
    }


} 