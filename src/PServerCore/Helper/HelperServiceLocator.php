<?php


namespace PServerCore\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class HelperServiceLocator
 * @package PServerCore\Helper
 *
 * @TODO rename with trait for 1.0
 */
trait HelperServiceLocator
{
    use HelperBasic;

    /**
     * @return ServiceLocatorInterface
     */
    public abstract function getServiceLocator();

    /**
     * @return ServiceLocatorInterface
     */
    public function getServiceManager()
    {
        return $this->getServiceLocator();
    }

}