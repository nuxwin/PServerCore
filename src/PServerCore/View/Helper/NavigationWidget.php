<?php


namespace PServerCore\View\Helper;

use Zend\View\Model\ViewModel;

class NavigationWidget extends InvokerBase
{

    /**
     * @return string
     */
    public function __invoke()
    {
        $viewModel = new ViewModel([
            'navigation' => $this->getConfig()['pserver']['navigation']
        ]);
        $viewModel->setTemplate('p-server-core/navigation');

        return $this->getView()->render($viewModel);
    }
}