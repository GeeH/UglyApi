<?php

namespace Core;

class Parameters
{
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var bool
     */
    private $returnEmpty = false;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Gets and filters a parameter
     *
     * @param $key
     * @param $filter
     * @return string
     */
    public function getParam($key, $filter)
    {
        if (!$this->keyExists($key)) {
            return '';
        }
        $filterClass = $this->getFilter($filter);

        if (class_exists($filterClass)) {
            $filter = new $filterClass();
            return $filter->filter($this->parameters[$key]);
        }
        return $this->{$filterClass}($this->parameters[$key]);
    }

    /**
     * Checks if the parameter exists
     *
     * @param $key
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function keyExists($key)
    {
        if ((!array_key_exists($key, $this->parameters) && !$this->returnEmpty)
        ) {
            throw new \InvalidArgumentException("Key `$key` does not exist");
        }
        if (!array_key_exists($key, $this->parameters) && $this->returnEmpty) {
            return false;
        }
        return true;
    }

    /**
     * Gets the filter class or method to filter given value
     *
     * @param $filter
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getFilter($filter)
    {
        if (class_exists('\\Zend\\Filter\\' . $filter)) {
            return '\\Zend\\Filter\\' . $filter;
        }
        if (class_exists('\\Zend\\I18n\\Filter\\' . $filter)) {
            return '\\Zend\\I18n\\Filter\\' . $filter;
        }

        if (method_exists($this, 'filter' . $filter)) {
            return 'filter' . $filter;
        }

        throw new \InvalidArgumentException("Filter `$filter` does not exist`");
    }

    /**
     * @return boolean
     */
    public function getReturnEmpty()
    {
        return $this->returnEmpty;
    }

    /**
     * @param boolean $returnEmpty
     */
    public function setReturnEmpty($returnEmpty)
    {
        $this->returnEmpty = $returnEmpty;
    }
}