<?php

namespace PServerCore\View\Helper;

use Zend\Mvc\Router\RouteInterface;
use Zend\Stdlib\RequestInterface;
use Zend\View\Helper\AbstractHelper;

class Active extends AbstractHelper
{
    /** @var  RouteInterface */
    protected $router;

    /** @var  RequestInterface */
    protected $request;

    /**
     * Active constructor.
     * @param RouteInterface $router
     * @param RequestInterface $request
     */
    public function __construct(RouteInterface $router, RequestInterface $request)
    {
        $this->router = $router;
        $this->request = $request;
    }

    /**
     * @param       $routeKey
     * @param array $params
     *
     * @return bool
     */
    public function __invoke($routeKey, $params = [])
    {
        $router = $this->router;
        $request = $this->request;

        $routeMatch = $router->match($request);

        if ($routeMatch === null) {
            return false;
        }

        if ($routeKey != $routeMatch->getMatchedRouteName()) {
            return false;
        }

        if (is_array($params) || $params instanceof \Traversable) {
            foreach ($params as $key => $param) {
                if ($router->match($request)->getParam($key) != $param) {
                    return false;
                }
            }
        }

        return true;
    }


}