<?php


namespace PServerCore\Options;

use Zend\Stdlib\AbstractOptions;

class PasswordOptions extends AbstractOptions
{
    protected $__strictMode__ = false;

    /**
     * @var bool
     */
    protected $differentPasswords = true;

    /**
     * @var bool
     */
    protected $secretQuestion = false;

    /**
     * @var array
     */
    protected $length = [
        'min' => 6,
        'max' => 32
    ];

    /** @var bool */
    protected $containsNumber = false;

    /** @var bool */
    protected $containsLowerLetter = false;

    /** @var bool */
    protected $containsUpperLetter = false;

    /** @var bool */
    protected $containsSpecialChar = false;

    /**
     * @return boolean
     */
    public function isDifferentPasswords()
    {
        return (bool)$this->differentPasswords;
    }

    /**
     * @param boolean $differentPasswords
     * @return PasswordOptions
     */
    public function setDifferentPasswords($differentPasswords)
    {
        $this->differentPasswords = $differentPasswords;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isSecretQuestion()
    {
        return (bool)$this->secretQuestion;
    }

    /**
     * @param boolean $secretQuestion
     * @return PasswordOptions
     */
    public function setSecretQuestion($secretQuestion)
    {
        $this->secretQuestion = $secretQuestion;

        return $this;
    }

    /**
     * @return array
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param array $length
     * @return PasswordOptions
     */
    public function setLength(array $length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isContainsNumber()
    {
        return $this->containsNumber;
    }

    /**
     * @param boolean $containsNumber
     * @return self
     */
    public function setContainsNumber($containsNumber)
    {
        $this->containsNumber = $containsNumber;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isContainsLowerLetter()
    {
        return $this->containsLowerLetter;
    }

    /**
     * @param boolean $containsLowerLetter
     * @return self
     */
    public function setContainsLowerLetter($containsLowerLetter)
    {
        $this->containsLowerLetter = $containsLowerLetter;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isContainsUpperLetter()
    {
        return $this->containsUpperLetter;
    }

    /**
     * @param boolean $containsUpperLetter
     * @return self
     */
    public function setContainsUpperLetter($containsUpperLetter)
    {
        $this->containsUpperLetter = $containsUpperLetter;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isContainsSpecialChar()
    {
        return $this->containsSpecialChar;
    }

    /**
     * @param boolean $containsSpecialChar
     * @return self
     */
    public function setContainsSpecialChar($containsSpecialChar)
    {
        $this->containsSpecialChar = $containsSpecialChar;
        return $this;
    }


}