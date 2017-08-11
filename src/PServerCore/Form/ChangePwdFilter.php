<?php

namespace PServerCore\Form;

use PServerCore\Options\PasswordOptions;
use PServerCore\Validator\PasswordRules;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class ChangePwdFilter extends InputFilter
{
    /**
     * @param PasswordOptions $passwordOptions
     */
    public function __construct(PasswordOptions $passwordOptions)
    {
        $passwordLengthOptions = $passwordOptions->getLength();

        $this->add([
            'name' => 'currentPassword',
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
            ],
        ]);

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


}