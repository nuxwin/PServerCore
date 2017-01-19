<?php

namespace PServerCore\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class CoinsInfoWidget extends AbstractHelper
{
    public function __invoke()
    {
        return $this->getView()->render(
            (new ViewModel())->setTemplate('helper/coins-info-widget')
        );
    }

}