<?php

namespace PServerCore\Service;

class ConfigRead
{
    /** @var  array */
    protected $config;

    /**
     * Caching the Config String
     * @var array
     */
    private $cache = [];

    /**
     * ConfigRead constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $configString
     * @param bool $default
     *
     * @return mixed
     */
    public function get($configString, $default = false)
    {
        // Check if we have a cache
        if (isset($this->cache[$configString])) {
            return $this->cache[$configString];
        }

        $valueList = explode('.', $configString);
        $config = $this->config;
        foreach ($valueList as $value) {
            if (!isset($config[$value])) {
                $config = $default;
                break;
            }
            $config = $config[$value];
        }

        // save @ cache
        $this->cache[$configString] = $config;

        return $config;
    }
}