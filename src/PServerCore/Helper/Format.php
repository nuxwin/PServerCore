<?php

namespace PServerCore\Helper;

/**
 * Class Format
 * @package PServerCore\Helper
 * @deprecated please use the \PServerCore\Service\Format
 */
class Format
{

    /**
     * @param int $length
     *
     * @return string
     */
    public static function getCode($length = 32)
    {
        $result = '';
        for ($i = 1; $i <= $length; $i++) {
            $result .= substr('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(0, 61), 1);
        }
        return $result;
    }
}
