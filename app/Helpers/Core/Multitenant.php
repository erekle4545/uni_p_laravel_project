<?php

namespace App\Helpers\Core;

/**
 * Class MultiTenant
 *
 * @package App\Helpers\Core
 */
abstract class Multitenant
{
    /**
     * Retrieve class based on given path
     */
    protected static function resolve($class, $path)
    {
            return $path . '' . $class;
    }


    /**
     * Retrieve model class
     */
    public static function getModel($class)
    {
       return self::resolve($class, 'App\Models\\');
    }
}
