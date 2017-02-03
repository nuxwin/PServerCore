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

        $result = $interval->format('%r%H:%I:%S');

        if ($interval->days > 0) {
            $result = sprintf(
                '%s day%s %s',
                $interval->days,
                $interval->days > 1 ? 's' : '',
                $result
            );
        }

        return $result;
    }

}