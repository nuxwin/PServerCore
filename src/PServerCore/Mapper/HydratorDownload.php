<?php

namespace PServerCore\Mapper;

use Exception;
use PServerCore\Entity\DownloadList;
use Zend\Hydrator\ClassMethods;

class HydratorDownload extends ClassMethods
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     *
     * @return array
     * @throws Exception
     */
    public function extract($object)
    {
        if (!$object instanceof DownloadList) {
            throw new Exception('$object must be an instance of Downloadlist');
        }
        /* @var $object DownloadList */
        $data = parent::extract($object);

        return $data;
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return DownloadList
     * @throws Exception
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof DownloadList) {
            throw new Exception('$object must be an instance of Downloadlist');
        }

        return parent::hydrate($data, $object);
    }
} 