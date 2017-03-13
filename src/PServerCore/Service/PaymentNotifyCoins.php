<?php

namespace PServerCore\Service;

use PServerCore\Entity\UserInterface;

class PaymentNotifyCoins
{
    /**
     * @param UserInterface $user
     * @param int $amount
     * @return int
     */
    public function getAmount(UserInterface $user, int $amount) : int
    {
        return $amount;
    }
}
