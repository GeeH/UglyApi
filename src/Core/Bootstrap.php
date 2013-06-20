<?php

namespace Core;

class Bootstrap
{

    /**
     * Default app os if none sent
     */
    const DEFAULT_APP_OS = 'mobi1';
    /**
     * Merged Configuration
     * @var array
     */
    protected $config = array();
    /**
     * @var Router
     */
    protected $router;
    /**
     * @var ServiceManager
     */
    protected $serviceManager;
    /**
     * @var string
     */
    protected $yamgoAppOs;

    /**
     * @param Router $router
     */
    function __construct(Router $router)
    {
        $this->router = $router;
        $config = $this->getConfig();
        $this->setServiceManager(new ServiceManager($config['service_manager'], $config['general']));
        $this->serviceManager->set('YamgoAppOs', $this->yamgoAppOs);
    }

    /**
     * Gets merged configuration (lazily loaded if needed)
     *
     * @return array
     */
    public function getConfig()
    {
        if (empty($this->config)) {
            return $this->getAndMergeConfigs();
        }
        return $this->config;
    }

    /**
     * Loads, merges and sets configuration
     *
     * @return array
     */
    protected function getAndMergeConfigs()
    {
        $this->config = array_merge(
            include('config/api.config.php'),
            include('config/db.config.php')
        );
        return $this->config;
    }

    /**
     * Looks for Yamgo-App-Os header, or uses the default
     *
     * @param array $headers
     * @return string
     */
    public function getYamgoAppOs(array $headers = array())
    {
        if (!is_string($this->yamgoAppOs)) {
            if (array_key_exists('Yamgo-App-Os', $headers)) {
                $this->setYamgoAppOs($headers['Yamgo-App-Os']);
            } else {
                $this->setYamgoAppOs(self::DEFAULT_APP_OS);
            }
        }
        return $this->yamgoAppOs;
    }

    /**
     * @param $yamgoAppOs
     */
    public function setYamgoAppOs($yamgoAppOs)
    {
        $this->yamgoAppOs = $yamgoAppOs;
    }

    public function init($uri, $method)
    {
        $route = $this->route($uri, $method);
        // get fqns of controller class
        $controllerClass = $this->getControllerClass($route);
        if (!$controllerClass) {
            throw new \InvalidArgumentException('Cannot route to controller `' . $route->getController() . '`');
        }
        $controller = new $controllerClass();
        // check the action method exists
        if (!method_exists($controllerClass, $route->getAction())) {
            throw new \InvalidArgumentException('Cannot route to action `' . $route->getAction() . '` in controller `'
            . $controllerClass . '`');
        }

        // dispatch the action
        return $this->dispatch($controller, $route->getAction());
    }

    /**
     * Grabs a valid and configured Route object from the Router class
     *
     * @param $uri
     * @param $method
     * @return Route
     */
    public function route($uri, $method)
    {
        return $this->router->route($uri, $method);
    }

    /**
     * Gets the first matching controller class that exists
     *
     * @param Route $route
     * @return bool|string
     */
    public function getControllerClass(Route $route)
    {
        if ($this->serviceManager->exists($route->getController())) {
            return $this->serviceManager->get($route->getController());
        }
        return false;
    }

    /**
     * Dispatches give controller and action and returns its result array
     *
     * @param object $controller
     * @param $action
     * @return array
     * @throws \InvalidArgumentException
     */
    public function dispatch($controller, $action)
    {
        $result = $controller->{$action}();
        if (!is_array($result)) {
            throw new \InvalidArgumentException('Action `' . $action . '` in controller `'
            . get_class($controller) . '` did not return a valid array: ' . gettype($result));
        }
        return $result;
    }

    /**
     * @return \Core\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param \Core\Router $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

    /**
     * @return \Core\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param \Core\ServiceManager $serviceManager
     */
    public function setServiceManager($serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }
}