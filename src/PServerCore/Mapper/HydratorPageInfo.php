<?php

namespace PServerCore\Mapper;

use PServerCore\Entity\PageInfo;
use Zend\Hydrator\ClassMethods;

class HydratorPageInfo extends ClassMethods
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
        if (!$object instanceof PageInfo) {
            throw new \Exception('$object must be an instance of PageInfo');
        }
        /* @var $object PageInfo */
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return PageInfo
     * @throws \Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof PageInfo) {
            throw new \Exception('$object must be an instance of PageInfo');
        }

        return parent::hydrate($data, $object);
    }
} 