<?php


namespace PServerCore\Form\Element;

use Zend\Form\Element\DateTimeLocal;

class DateTimeJQuery extends DateTimeLocal
{
    /** @var string */
    const DATETIME_FORMAT = 'Y-m-d';

    /**
     * Seed attributes
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'text',
    ];

    /** @var string */
    protected $format = self::DATETIME_FORMAT;

}