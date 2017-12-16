<?php

/**
 * Created by PhpStorm.
 * User: franck
 * Date: 19/09/2017
 * Time: 20:16
 */
class Events
{
    public $title = '';
    public $start = '';
    public $end = '';
    public $type = '';



    public function toString() {
        $arr = [];
        $arr["title"] = $this->title;
        $arr["start"] = $this->start;
        $arr["end"] = $this->end;
        $arr["type"] = $this->type;
        return json_encode($arr);
    }
}