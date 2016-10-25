<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BaseConfigured;

use \Nette\DI\Config\Loader;
use \Nette\Caching\Cache;
use \Nette\Caching\Storages\FileStorage;

/**
 * Description of ConfigLoader
 *
 * @author chlad
 */
class ConfigLoader
{
    
    const CACHE_NAME = 'config';
    const CACHE_TEMP = 'temp';
    /**
     * load config
     */
    public static function loadConfig($path, $cacheDirectory=self::CACHE_TEMP)
    {
        $storage = new FileStorage($cacheDirectory);
        $cache = new Cache($storage);
        
        $confData = $cache->load(static::CACHE_NAME);
        if ($confData === null)
        {
            $loader = new Loader();
            $confData = $loader->load($path);
            # resolve inheritance etc ...
            ConfigParser::recursiveResolve($confData, $confData);
            $cache->save(static::CACHE_NAME, $confData);
        }
        return $confData;
    }
    
    
}
