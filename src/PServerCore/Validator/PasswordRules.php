<?php


namespace PServerCore\Validator;

use PServerCore\Options\PasswordOptions;
use Zend\Validator\AbstractValidator;

class PasswordRules extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_NO_NUMBER = 'noNumber';
    const ERROR_NO_LOWER_CASE_LETTER = 'noLowerCaseLetter';
    const ERROR_NO_UPPER_CASE_LETTER = 'noUpperCaseLetter';
    const ERROR_NO_SPECIAL_CHAR = 'noSpecialChar';

    /**
     * TODO better message
     * @var array Message templates
     */
    protected $messageTemplates = [
        self::ERROR_NO_NUMBER => "Must contain at least one digit character",
        self::ERROR_NO_LOWER_CASE_LETTER => "Must contain an lowercase  letter",
        self::ERROR_NO_UPPER_CASE_LETTER => 'Must contain an uppercase letter',
        self::ERROR_NO_SPECIAL_CHAR => 'Must contain a special character, like "\'/~`!@#$%^&*()_-+={}[]|;:"<>,.?\"'
    ];

    /** @var  PasswordOptions */
    protected $passwordOptions;

    /**
     * PasswordRules constructor.
     * @param PasswordOptions $passwordOptions
     */
    public function __construct(PasswordOptions $passwordOptions)
    {
        parent::__construct();
        $this->passwordOptions = $passwordOptions;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        $result = true;

        if ($this->passwordOptions->isContainsNumber() && preg_match('/[0-9]/', $value) !== 1) {
            $this->error(self::ERROR_NO_NUMBER);
            $result = false;
        }

        if ($this->passwordOptions->isContainsLowerLetter() && preg_match('/[a-z]/', $value) !== 1) {
            $this->error(self::ERROR_NO_LOWER_CASE_LETTER);
            $result = false;
        }

        if ($this->passwordOptions->isContainsUpperLetter() && preg_match('/[A-Z]/', $value) !== 1) {
            $this->error(self::ERROR_NO_UPPER_CASE_LETTER);
            $result = false;
        }

        if ($this->passwordOptions->isContainsSpecialChar() && preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/',
                $value) !== 1
        ) {
            $this->error(self::ERROR_NO_SPECIAL_CHAR);
            $result = false;
        }

        return $result;
    }

}