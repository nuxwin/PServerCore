<?php

namespace PServerCore\Mapper;

use PServerCore\Entity\News;
use Zend\Hydrator\ClassMethods;

class HydratorNews extends ClassMethods
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
        if (!$object instanceof News) {
            throw new \Exception('$object must be an instance of News');
        }
        /* @var $object News */
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return News
     * @throws \Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof News) {
            throw new \Exception('$object must be an instance of News');
        }

        return parent::hydrate($data, $object);
    }
} 