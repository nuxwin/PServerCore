<?php

namespace PServerCore\Form;

use Zend\Captcha\AdapterInterface;
use Zend\Form;

class PwLost extends Form\Form
{

    /**
     * PwLost constructor.
     * @param AdapterInterface $adapterInterface
     */
    public function __construct(AdapterInterface $adapterInterface)
    {
        parent::__construct();

        $this->add([
            'type' => Form\Element\Csrf::class,
            'name' => 'eugzhoe45gh3o49ug2wrtu7gz50'
        ]);

        $this->add([
            'name' => 'username',
            'options' => [
                'label' => 'Username',
            ],
            'attributes' => [
                'class' => 'form-control',
                'type' => 'text',
                'required' => true,
            ],
        ]);

        $captcha = new Form\Element\Captcha('captcha');
        $captcha->setCaptcha($adapterInterface)
            ->setOptions([
                'label' => 'Please verify you are human.',
            ])
            ->setAttributes([
                'class' => 'form-control',
                'type' => 'text',
                'required' => true,
            ]);
        $this->add($captcha);

        $submitElement = new Form\Element\Button('submit');
        $submitElement
            ->setLabel('PwLost')
            ->setAttributes([
                'class' => 'btn btn-default',
                'type' => 'submit',
            ]);

        $this->add($submitElement, [
            'priority' => -100,
        ]);

    }
} 