<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 26/09/2018
 * Time: 23:20
 */

class HelperFranck {

    public static function array_find_deep($array, $search, $keys = array())
    {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $sub = self::array_find_deep($value, $search, array_merge($keys, array($key)));
                if (count($sub)) {
                    return $sub;
                }
            } elseif ($value === $search) {
                return array_merge($keys, array($key));
            }
        }
        return array();
    }

    public static function object_to_array($obj) {
        if(is_object($obj)) {
            $obj = (array) self::dismount($obj);
        }
        if(is_array($obj)) {
            $new = [];
            foreach($obj as $key => $val) {
                $new[$key] = self::object_to_array($val);
            }
        }
        else {
            $new = $obj;
        }
        return $new;
    }

    public static function dismount($object) {
        $reflectionClass = new ReflectionClass(get_class($object));
        $array = [];
        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }
        return $array;
    }
}