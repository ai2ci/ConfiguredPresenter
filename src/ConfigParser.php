<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ConfiguredPresenters;

use Nette\DI\Config\Helpers;

/**
 * Description of ConfigParser
 *
 * @author chlad
 */
class ConfigParser
{

    /**
     * returns value in the end of the path
     * @params array path indexes
     * @params array data
     * @return mixin
     */
    public static function &getInheritedVariable(array $params, array &$originData = null)
    {
        $value = &$originData;
        foreach ($params as $param) {
            # value simply defined
            $value = &$value[$param];
            # check inheriting
            $parent = Helpers::takeParent($value);
            # overwrite inherited value
            if ($parent) {
                $inherited = self::getInheritedVariable(explode('.', $parent), $originData);
                $value = Helpers::merge($inherited, $value);
            }
        }
        return $value;
    }

    /**
     * resolves inheritance setting values in $data array
     * @param array $data
     * @param arrray $originData
     */
    public static function recursiveResolve(array &$data, array &$originData)
    {
        foreach ($data as $key => $value) {
            # check inheriting
            $parent = Helpers::takeParent($value);
            # overwrite inherited value
            if ($parent) {
                $inherited = self::getInheritedVariable(explode('.', $parent), $originData);
                $data[$key] = Helpers::merge($inherited, $value);
            }
            if (is_array($data[$key])) {
                self::recursiveResolve($data[$key], $originData);
            }
        }
    }

}
