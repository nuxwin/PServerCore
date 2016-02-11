<?php


namespace PServerCore\View\Helper;



class CoinsWidget extends InvokerBase
{
    /**
     * @return int
     */
    public function __invoke()
    {
        return $this->getCoinService()->getCoinsOfUser($this->getAuthService()->getIdentity());
    }
}