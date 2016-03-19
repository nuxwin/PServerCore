<?php


namespace PServerCore\View\Helper;

use PServerCore\Service\ServerInfo;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;

class ServerInfoWidget extends AbstractHelper
{
    /** @var  ServerInfo */
    protected $serverInfoService;

    /**
     * ServerInfoWidget constructor.
     * @param ServerInfo $serverInfoService
     */
    public function __construct(ServerInfo $serverInfoService)
    {
        $this->serverInfoService = $serverInfoService;
    }

    /**
     * @return string
     */
    public function __invoke()
    {
        $viewModel = new ViewModel([
            'serverInfo' => $this->serverInfoService->getServerInfo()
        ]);
        $viewModel->setTemplate('helper/sidebarServerInfoWidget');

        return $this->getView()->render($viewModel);
    }
}