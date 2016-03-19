<?php


namespace PServerCore\Options;


use Doctrine\ORM\EntityManager;

class Collection
{
    /** @var  EntityManager */
    protected $entityOptions;

    /** @var  GeneralOptions */
    protected $generalOptions;

    /** @var  LoginOptions */
    protected $loginOptions;

    /** @var  MailOptions */
    protected $mailOptions;

    /** @var  PasswordOptions */
    protected $passwordOptions;

    /** @var  RegisterOptions */
    protected $registerOptions;

    /** @var  UserCodeOptions */
    protected $userCodesOptions;

    /** @var  ValidationOptions */
    protected $validationOptions;

    /**
     * @return EntityOptions
     */
    public function getEntityOptions()
    {
        return $this->entityOptions;
    }

    /**
     * @param EntityManager $entityOptions
     * @return self
     */
    public function setEntityOptions($entityOptions)
    {
        $this->entityOptions = $entityOptions;
        return $this;
    }

    /**
     * @return GeneralOptions
     */
    public function getGeneralOptions()
    {
        return $this->generalOptions;
    }

    /**
     * @param GeneralOptions $generalOptions
     * @return self
     */
    public function setGeneralOptions($generalOptions)
    {
        $this->generalOptions = $generalOptions;
        return $this;
    }

    /**
     * @return LoginOptions
     */
    public function getLoginOptions()
    {
        return $this->loginOptions;
    }

    /**
     * @param LoginOptions $loginOptions
     * @return self
     */
    public function setLoginOptions($loginOptions)
    {
        $this->loginOptions = $loginOptions;
        return $this;
    }

    /**
     * @return MailOptions
     */
    public function getMailOptions()
    {
        return $this->mailOptions;
    }

    /**
     * @param MailOptions $mailOptions
     * @return self
     */
    public function setMailOptions($mailOptions)
    {
        $this->mailOptions = $mailOptions;
        return $this;
    }

    /**
     * @return PasswordOptions
     */
    public function getPasswordOptions()
    {
        return $this->passwordOptions;
    }

    /**
     * @param PasswordOptions $passwordOptions
     * @return self
     */
    public function setPasswordOptions($passwordOptions)
    {
        $this->passwordOptions = $passwordOptions;
        return $this;
    }

    /**
     * @return RegisterOptions
     */
    public function getRegisterOptions()
    {
        return $this->registerOptions;
    }

    /**
     * @param RegisterOptions $registerOptions
     * @return self
     */
    public function setRegisterOptions($registerOptions)
    {
        $this->registerOptions = $registerOptions;
        return $this;
    }

    /**
     * @return UserCodeOptions
     */
    public function getUserCodesOptions()
    {
        return $this->userCodesOptions;
    }

    /**
     * @param UserCodeOptions $userCodesOptions
     * @return self
     */
    public function setUserCodesOptions($userCodesOptions)
    {
        $this->userCodesOptions = $userCodesOptions;
        return $this;
    }

    /**
     * @return ValidationOptions
     */
    public function getValidationOptions()
    {
        return $this->validationOptions;
    }

    /**
     * @param ValidationOptions $validationOptions
     * @return self
     */
    public function setValidationOptions($validationOptions)
    {
        $this->validationOptions = $validationOptions;
        return $this;
    }


}