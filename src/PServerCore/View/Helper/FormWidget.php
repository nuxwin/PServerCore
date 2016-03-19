<?php

namespace PServerCore\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class FormWidget extends AbstractHelper
{
    /**
     * @param $form
     * @param string $template
     * @return string
     */
    public function __invoke($form, $template = 'helper/formWidget')
    {
        $viewModel = new ViewModel([
            'formWidget' => $form
        ]);
        $viewModel->setTemplate($template);

        return $this->getView()->render($viewModel);
    }

}