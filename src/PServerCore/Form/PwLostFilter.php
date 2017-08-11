<?php

namespace PServerCore\Form;

use PServerCore\Validator\AbstractRecord;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class PwLostFilter extends InputFilter
{
    /** @var  AbstractRecord */
    protected $userValidator;

    /**
     * @param AbstractRecord $userValidator
     */
    public function __construct(AbstractRecord $userValidator)
    {
        $this->setUserValidator($userValidator);

        $this->add([
            'name' => 'username',
            'required' => true,
            'validators' => [
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => 3,
                        'max' => 16,
                    ],
                ],
                $this->getUserValidator(),
            ],
        ]);
    }

    /**
     * @return AbstractRecord
     */
    public function getUserValidator()
    {
        return $this->userValidator;
    }

    /**
     * @param AbstractRecord $userValidator
     *
     * @return $this
     */
    public function setUserValidator($userValidator)
    {
        $this->userValidator = $userValidator;
        return $this;
    }
} 