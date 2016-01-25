<?php


namespace PServerCore\Helper;


trait HelperOptions
{

    /**
     * @return \PServerCore\Options\EntityOptions
     */
    protected function getEntityOptions()
    {
        return $this->getService('pserver_entity_options');
    }

    /**
     * @return \PServerCore\Options\MailOptions
     */
    protected function getMailOptions()
    {
        return $this->getService('pserver_mail_options');
    }

    /**
     * @return \PServerCore\Options\PasswordOptions
     */
    protected function getPasswordOptions()
    {
        return $this->getService('pserver_password_options');
    }

    /**
     * @return \PServerCore\Options\GeneralOptions
     */
    protected function getGeneralOptions()
    {
        return $this->getService('pserver_general_options');
    }

    /**
     * @return \PServerCore\Options\LoginOptions
     */
    protected function getLoginOptions()
    {
        return $this->getService('pserver_login_options');
    }

    /**
     * @return \PServerCore\Options\RegisterOptions
     */
    protected function getRegisterOptions()
    {
        return $this->getService('pserver_register_options');
    }

    /**
     * @return \PServerCore\Options\ValidationOptions
     */
    protected function getValidationOptions()
    {
        return $this->getService('pserver_validation_options');
    }

    /**
     * @return \PServerCore\Options\UserCodeOptions
     */
    protected function getUserCodeOptions()
    {
        return $this->getService('pserver_user_code_options');
    }

    /**
     * @param $serviceName
     *
     * @return array|object
     */
    public abstract function getService($serviceName);
}