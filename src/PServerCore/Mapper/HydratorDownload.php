<?php

namespace PServerCore\Mapper;

use PServerCore\Entity\Downloadlist;
use Zend\Hydrator\ClassMethods;

class HydratorDownload extends ClassMethods
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
        if (!$object instanceof Downloadlist) {
            throw new \Exception('$object must be an instance of Downloadlist');
        }
        /* @var $object Downloadlist */
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return Downloadlist
     * @throws \Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Downloadlist) {
            throw new \Exception('$object must be an instance of Downloadlist');
        }

        return parent::hydrate($data, $object);
    }
} 