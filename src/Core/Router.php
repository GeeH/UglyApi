<?php

namespace Core;

use Prophecy\Exception\InvalidArgumentException;

class Router
{
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = '';
    /**
     * @var Route
     */
    protected $route;

    public function __construct()
    {
        $this->route = new Route();
    }

    public function route($uri, $method)
    {
        $parsedUri = $this->parseUri($uri);
        $controller = ucfirst($parsedUri['controller'] . 'Controller');
        $this->route->setController($controller);

        $action = strtolower($parsedUri['action']) . ucfirst(strtolower($method)) . 'Action';
        $action = lcfirst($action);
        $this->route->setAction($action);

        $get = (isset($_GET)) ? $_GET : array();
        $post = (isset($_POST)) ? $_POST : array();

        $this->route->setParams($this->getMergedParams($get, $post, $parsedUri['params']));

        if ($controller === 'ChannelController' && isset($parsedUri['params'][2])
            && (int)$parsedUri['params'][2] > 0 && $method === 'GET'
        ) {
            $this->route->setAction('getChannelGetAction');
            $this->route->setParams(array('channelId' => $parsedUri['params'][2]));
        }

        return $this->route;
    }

    /**
     * Parses URI in controller, action and params
     *
     * @param $uri
     * @return array
     * @throws \Prophecy\Exception\InvalidArgumentException
     */
    public function parseUri($uri)
    {
        $uri = parse_url($uri);
        if (!array_key_exists('path', $uri) || empty($uri['path'])) {
            throw new InvalidArgumentException('Invalid URI passed: ' . json_encode($uri));
        }
        $parts = explode('/', $uri['path']);
        $return = array();
        // if controller set, use it, otherwise use default
        $return['controller'] = (empty($parts[1])) ? self::DEFAULT_CONTROLLER : $parts[1];
        // if action set, use it, otherwise use default
        $return['action'] = (empty($parts[2])) ? self::DEFAULT_ACTION : $parts[2];
        // if params set, return them
        $return['params'] = array_slice($parts, 3);
        return $return;
    }

    /**
     * Merges parameters in the right order, GET < POST < PARAMS
     *
     * @param array $get
     * @param array $post
     * @param array $params
     * @return array
     */
    public function getMergedParams(array $get, array $post, array $params)
    {
        $queryParams = $get;
        $queryParams = array_merge($queryParams, $post);
        foreach ($params as $key => $val) {
            if ($key % 2 != 0 && isset($params[$key - 1])) {
                $queryParams[$params[$key - 1]] = $val;
            }
        }
        return $queryParams;
    }

}