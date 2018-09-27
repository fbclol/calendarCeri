<?php

require_once "TypeCour.php";
require_once "CollectionTypesCours.php";
class Event
{
    public $title    = '';
    public $start    = '';
    public $end      = '';
    public $type     = '';
    public $location = '';

    public function toString() {
        $arr = [];
        $arr["title"] = $this->title;
        $arr["start"] = $this->start;
        $arr["end"] = $this->end;
        $arr["type"] = $this->type;
        $arr["location"] = $this->location;
        return json_encode($arr);
    }

    /**
     * @param string $title
     */
    public function setType(string $title)
    {
        $oCollectionTypesCours = new CollectionTypesCours();
        $aTypesCours           =  $oCollectionTypesCours->getCollectionTypesCours();
        $aTypesCoursIndexName  = array_column($aTypesCours, 'name',"name");

       foreach ($aTypesCoursIndexName as $oValue) {
           if (preg_match('/'.$oValue.'/i', $title) === 1) {
               $this->type = $oValue;
           }
       }
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @param string $start
     */
    public function setStart(string $start)
    {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @param string $end
     */
    public function setEnd(string $end)
    {
        $this->end = $end;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

}