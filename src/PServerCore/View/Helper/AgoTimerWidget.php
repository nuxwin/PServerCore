<?php

namespace PServerCore\View\Helper;

use DateTime;
use Zend\View\Helper\AbstractHelper;

class AgoTimerWidget extends AbstractHelper
{
    /**
     * @param DateTime $dateTime
     * @return string
     */
    public function __invoke(DateTime $dateTime)
    {
        $interval = $dateTime->diff(new DateTime());

        return $interval->format('%r%H:%I:%S');
    }

}