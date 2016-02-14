<?php


namespace PServerCore\Mapper;

use PServerCore\Entity\UserBlock;
use Zend\Hydrator\ClassMethods;

class HydratorUserBlock extends ClassMethods
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
        if (!$object instanceof UserBlock) {
            throw new \Exception('$object must be an instance of UserBlock');
        }
        /* @var $object UserBlock */
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return UserBlock
     * @throws \Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof UserBlock) {
            throw new \Exception('$object must be an instance of UserBlock');
        }

        return parent::hydrate($data, $object);
    }
}