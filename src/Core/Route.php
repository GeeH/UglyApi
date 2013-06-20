<?php

namespace Core;

class Route
{
    /**
     * @var string
     */
    private $controller;
    /**
     * @var string
     */
    private $action;
    /**
     * @var Parameters
     */
    private $params;

    /**
     * @param $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = new Parameters($params);
    }

    /**
     * @return Parameters
     */
    public function getParams()
    {
        return $this->params;
    }
}