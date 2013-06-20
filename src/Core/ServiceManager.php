<?php

namespace Core;

class ServiceManager
{
    /**
     * @var array
     */
    private $services = array();
    /**
     * @var array
     */
    private $hydratedServices = array();

    /**
     * @param array $config
     * @param array $general
     */
    public function __construct(array $config, array $general)
    {
        $this->set('config', $general);
        if (array_key_exists('factories', $config)) {
            $this->services = $config['factories'];
        }
    }

    /**
     * Gets a hydrated service, or hydrates and returns a non-hydrated service
     *
     * @param $key
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->hydratedServices)) {
            return $this->hydratedServices[$key];
        }
        if (!array_key_exists($key, $this->services)) {
            throw new \InvalidArgumentException("No service by key `$key` exists");
        }
        $service = call_user_func($this->services[$key], $this);
        $this->hydratedServices[$key] = $service;
        return $service;
    }

    /**
     * Does the given key exists in a hydrated or non-hydrated state?
     *
     * @param $key
     * @return bool
     */
    public function exists($key)
    {
        return (array_key_exists($key, $this->hydratedServices)
            || array_key_exists($key, $this->services));
    }

    /**
     * Sets a pre-hydrated service
     *
     * @param $key
     * @param $service
     */
    public function set($key, $service)
    {
        $this->hydratedServices[$key] = $service;
    }
}