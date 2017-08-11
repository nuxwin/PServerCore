<?php

namespace PServerCore\View\Helper;

use DateTime;
use PServerCore\Options\GeneralOptions;
use Zend\View\Helper\AbstractHelper;

class DateTimeFormat extends AbstractHelper
{
    /** @var  GeneralOptions */
    protected $generalOptions;

    /**
     * DateTimeFormat constructor.
     * @param GeneralOptions $generalOptions
     */
    public function __construct(GeneralOptions $generalOptions)
    {
        $this->generalOptions = $generalOptions;
    }

    /**
     * @param DateTime $dateTime
     * @return string
     */
    public function __invoke(DateTime $dateTime)
    {
        return $dateTime->format($this->getConfigFormat());
    }

    /**
     * @return string
     */
    public function getConfigFormat()
    {
        return $this->generalOptions->getDatetime()['format']['time'];
    }
}