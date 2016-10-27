<?php

namespace ConfiguredPresenters;

# constants
const BASE_CONFIGURED_SECTION = 'Base';
const BEEN_HERE_CONFIGURED_SECTION = 'onBeenHere';
const TEMPLATE_SECTION = 'template';

/**
 * Base trait for all application presenters which are configured
 */
trait BaseConfiguredTrait
{
    /** @var array class variable source */
    public $__data = [];

    /** @var array */
    public $__countedRequest = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * return used name of this instance
     * @return string
     */
    public function getConfName()
    {
        return $this->getName();
    }

    /**
     * return used action of this instance
     * @return string
     */
    public function getConfAction()
    {
        return $this->getAction();
    }

    public function startup()
    {
        parent::startup();
        #init local variables
        $name = $this->getConfName();
        $action = $this->getConfAction();
        $config = ConfigLoader::loadConfig();
        $counter = new VisitCounter($name, $action);

        # import base configuration
        $this->initVariables($config[BASE_CONFIGURED_SECTION]);
        # import current action configuration
        $this->initVariables($config[$name][$action]);
        # import beenHere current action configuration 
        if ($counter->countOfBeenHere() > 0) {
            $this->initVariables($config[$name][$action][BEEN_HERE_CONFIGURED_SECTION]);
        }
    }

    public function &__get($name)
    {
        switch ($name) {
            case TEMPLATE_SECTION:
            case BEEN_HERE_CONFIGURED_SECTION:
                return parent::__get($name);
            default:
                if (!property_exists($this, $name)) {
                    return $this->__data[$name];
                } else {
                    return parent::__get($name);
                }
                break;
        }
    }

    public function __set($name, $value)
    {
        $this->__data[$name] = $value;
    }

    /**
     * add variables as object properties
     * @param array $array
     */
    public function initVariables($array)
    {
        foreach ($array as $key => $val) {
            # value simply defined
            $this->{$key} = $val;
        }
    }
}