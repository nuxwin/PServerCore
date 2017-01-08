<?php

namespace PServerCore\View\Helper;

use Zend\Form\View\Helper\Captcha\Image;
use Zend\View\Model\ViewModel;

class CaptchaImageReload extends Image
{
    /**
     * @return string
     */
    public function getSeparator()
    {
        return $this->getView()->render(
            (new ViewModel())->setTemplate('helper/captcha-image-reload')
        );
    }

}