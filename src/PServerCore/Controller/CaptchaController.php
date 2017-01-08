<?php

namespace PServerCore\Controller;

use Zend\Captcha\Image;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class CaptchaController extends AbstractActionController
{
    /** @var  Image */
    protected $captchaService;

    /**
     * CaptchaController constructor.
     * @param Image $captchaService
     */
    public function __construct(Image $captchaService)
    {
        $this->captchaService = $captchaService;
    }

    /**
     * @return JsonModel
     */
    public function reloadAction()
    {
        return new JsonModel([
            'id' => $this->captchaService->generate(),
            'url' => $this->captchaService->getImgUrl(),
        ]);
    }
}