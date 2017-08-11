<?php

namespace PServerCore\Form;

use Zend\Form;

class AddEmail extends Form\Form
{

    /**
     * AddEmail constructor.
     */
    public function __construct()
    {

        parent::__construct();

        $this->add([
            'type' => Form\Element\Csrf::class,
            'name' => 'eugzhoe45gh3o49ug2wrtu7gz50'
        ]);

        $this->add([
            'name' => 'email',
            'options' => [
                'label' => 'Email',
            ],
            'attributes' => [
                'placeholder' => 'Email',
                'class' => 'form-control',
                'type' => 'email',
                'required' => true,
            ],
        ]);
        $this->add([
            'name' => 'emailVerify',
            'options' => [
                'label' => 'Email Verify',
            ],
            'attributes' => [
                'placeholder' => 'Email Verify',
                'class' => 'form-control',
                'type' => 'email',
                'required' => true,
            ],
        ]);

        $submitElement = new Form\Element\Button('submit');
        $submitElement->setLabel('Submit')
            ->setAttributes([
                'class' => 'btn btn-primary',
                'type' => 'submit',
            ]);

        $this->add($submitElement, [
            'priority' => -100,
        ]);

    }
}