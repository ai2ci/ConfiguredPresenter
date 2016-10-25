<?php

namespace BaseConfigured;

/**
 * Base presenter for all application presenters.
 */
trait BaseConfiguredTrait 
{

    public $__BASE_NAME = 'Base';
    public $__CACHE_NAME = 'base-configured-pages';
    public $__data = [];

    /** @var array */
    public $__countedRequest = [];

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getConfName()
    {
        return $this->getName();
    }
    public function getConfAction()
    {
        return $this->getAction();
    }

    public function startup()
    {
        parent::startup();
        $config = ConfigLoader::loadConfig();
        # import base configuration
        $this->__initVariables($config['Base']);
        # import current action configuration
        $this->__initVariables($config[$this->getConfName()][$this->getConfAction()]);
        
        $counter = $this->_countOfBeenHere();
        
        # import beenHere current action configuration 
        if ($counter > 0)
        {
            $this->__importVariables($config[$this->getConfName()][$this->getConfAction()]["onBeenHere"]);
        }
    }

    public function &__get($name)
    {
        switch ($name)
        {
            case 'template':

                return parent::__get($name);

            default:
                if (!property_exists($this, $name))
                {
                    return $this->__data[$name];
                }
                else
                {
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
     * default cookie name
     * @return string
     */
    protected function _getCookieName($cookieName = null)
    {
        # automatic cookie name
        if ($cookieName === null)
        {
            $cookieName = implode("-",[ 'configured', $this->getName(), $this->getAction(), "been-here"]);
        }
        return $cookieName;
    }

    /**
     * return count of been here
     * @param string $cookieName
     * @return int
     */
    protected function _countOfBeenHere($cookieName = null)
    {
        $count = 0;
        $beenHere = @$_GET['beenHere'];
        # forced from URL
        if (isset($beenHere))
        {
            $count = intval($beenHere);
        }
        # normally from cookie
        else
        {
            $cookieName = $this->_getCookieName($cookieName);
            
            # if has been set then directly get value
            if (isset($this->__countedRequest[$cookieName]))
            {
                $count = $this->__countedRequest[$cookieName];
            }
            # inrease value
            else
            {
                $count = $this->getHttpRequest()->getCookie($cookieName) ? : 0;
                $count++;
                $this->getHttpResponse()->setCookie($cookieName, $count, time() + 3600 * 24 * 7);
                $this->__countedRequest[$cookieName] = $count;
                $count--;
            }
        }
        return $count;
    }

    public function __initVariables($value)
    {
        foreach ($value as $key => $val)
        {
            # value simply defined
            $this->{$key} = $val;
        }
    }

}
