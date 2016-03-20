<?php


namespace PServerCore\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class NavigationWidget extends AbstractHelper
{
    /** @var  array */
    protected $config;

    /**
     * NavigationWidget constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $viewModel = new ViewModel([
            'navigation' => $this->config['navigation']
        ]);
        $viewModel->setTemplate('p-server-core/navigation');

        return $this->getView()->render($viewModel);
    }
}