<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 27/09/2018
 * Time: 02:45
 */

class TypeCour
{

    private $name;



    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function __isset($prop) : bool
    {
        return isset($this->$prop);
    }

}