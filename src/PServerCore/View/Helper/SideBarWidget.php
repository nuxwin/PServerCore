<?php

namespace PServerCore\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class SideBarWidget extends AbstractHelper
{
    /**
     * @return string
     */
    public function __invoke()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('helper/sidebarWidget');

        return $this->getView()->render($viewModel);
    }
}