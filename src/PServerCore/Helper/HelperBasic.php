<?php


namespace PServerCore\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class HelperBasic
 * @package PServerCore\Helper
 *
 * @TODO rename with trait for 1.0
 */
trait HelperBasic
{
    /** @var  array */
    protected $serviceCache;

    /**
     * @return ServiceLocatorInterface
     */
    public abstract function getServiceManager();

    /**
     * @param $serviceName
     *
     * @return array|object
     */
    protected function getService($serviceName)
    {
        if (!isset($this->serviceCache[$serviceName])) {
            $this->serviceCache[$serviceName] = $this->getServiceManager()->get($serviceName);
        }

        return $this->serviceCache[$serviceName];
    }

}