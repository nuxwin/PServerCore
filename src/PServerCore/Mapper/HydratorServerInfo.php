<?php

namespace PServerCore\Mapper;

use PServerCore\Entity\ServerInfo;
use Zend\Hydrator\ClassMethods;

class HydratorServerInfo extends ClassMethods
{

    /**
     * Extract values from an object
     *
     * @param  object $object
     *
     * @return array
     * @throws \Exception
     */
    public function extract($object)
    {
        if (!$object instanceof ServerInfo) {
            throw new \Exception('$object must be an instance of ServerInfo');
        }
        /* @var $object ServerInfo */
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return ServerInfo
     * @throws \Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof ServerInfo) {
            throw new \Exception('$object must be an instance of ServerInfo');
        }

        return parent::hydrate($data, $object);
    }
} 