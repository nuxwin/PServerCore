<?php


namespace PServerCore\Mapper;

use PServerCore\Entity\SecretQuestion;
use Zend\Hydrator\ClassMethods;

class HydratorSecretQuestion extends ClassMethods
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
        if (!$object instanceof SecretQuestion) {
            throw new \Exception('$object must be an instance of SecretQuestion');
        }
        /* @var $object SecretQuestion */
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return SecretQuestion
     * @throws \Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof SecretQuestion) {
            throw new \Exception('$object must be an instance of SecretQuestion');
        }

        return parent::hydrate($data, $object);
    }
}