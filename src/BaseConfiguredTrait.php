<?php

namespace \BaseConfigured;

use Nette;
use Nette\DI;

/**
 * Base presenter for all application presenters.
 */
trait BaseConfiguredTrait
{

    public $__BASE_NAME = 'Base';
    public $__CACHE_NAME = 'base-configured-pages';
    public $__data = [];
    public $__confData = [];

    /** @var Nette\Caching\Cache */
    public $_cache;

    /** @var array */
    public $__countedRequest = [];

    public function __construct()
    {
        parent::__construct();

        $storage = new Nette\Caching\Storages\FileStorage('temp');
        $this->_cache = new Nette\Caching\Cache($storage);
    }
    
    public function getConfiguredName()
    {
        return $this->getName();
    }
    public function getConfiguredAction()
    {
        return $this->getAction();
    }

    public function startup()
    {
        parent::startup();
        $this->__loadConfig();
        # import base configuration
        $this->__importVariables(['Base']);
        # import current action configuration
        $this->__importVariables([$this->getConfiguredName(), $this->getConfiguredAction()]);
        $counter = $this->_countOfBeenHere();
        # import beenHere current action configuration 
        if ($counter > 0)
        {
            $this->__importVariables([$this->getConfiguredName(), $this->getConfiguredAction(), "onBeenHere"]);
//            $this->__importVariables([$this->getName(), $this->getAction(), "onBeenHere{$counter}"]);
        }
    }

    /**
     * 
     * @params array path indexes
     * @return mixin
     */
    private function __importVariables($params)
    {
        $value = $this->__getInheritedVariable($params) ? : [];

        foreach ($value as $key => $val)
        {
            # value simply defined
            $this->{$key} = $val;
        }
    }

    /**
     * 
     * @params array path indexes
     * @return mixin
     */
    private function &__getInheritedVariable($params, &$originData = null)
    {
        $value = ($originData ? : $this->__confData);
        foreach ($params as $param)
        {
            # value simply defined
            $value = &$value[$param];
            # check inheriting
            $parent = DI\Config\Helpers::takeParent($value);
            # overwrite inherited value
            if ($parent)
            {
                $inherited = $this->__getInheritedVariable(explode('.', $parent),$originData);
                $value = DI\Config\Helpers::merge($inherited, $value);
            }
        }
        return $value;
    }
    
    /**
     * resolve inheritance setting value
     */
    private function _recursivelyResolve(&$data, &$originData)
    {
        foreach ($data as $key => $value)
        {
            # check inheriting
            $parent = DI\Config\Helpers::takeParent($value);
            # overwrite inherited value
            if ($parent)
            {
                $inherited = $this->__getInheritedVariable(explode('.', $parent), $originData);
                $data[$key] = DI\Config\Helpers::merge($inherited, $value);
            }
            if (is_array($data[$key])) 
            {
                $this->_recursivelyResolve($data[$key], $originData);
            }
        }
    }

    /**
     * load config
     */
    private function __loadConfig()
    {
        $file = __DIR__ . '/../config/pages.neon';
        $this->__confData = $this->_cache->load($this->__CACHE_NAME);
        if ($this->__confData === null)
        {
            $loader = new DI\Config\Loader();
            $helper = new DI\Config\Helpers();
            $this->__confData = $loader->load($file);
            # resolve inheritance etc ...
            $this->_recursivelyResolve($this->__confData, $this->__confData);
            $this->_cache->save($this->__CACHE_NAME, $this->__confData);
        }
    }

    public function &__get($name)
    {
        switch ($name)
        {
            case 'template':

                return parent::__get($name);
                break;

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
    
}
