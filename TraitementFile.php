<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 26/09/2018
 * Time: 23:56
 */
require_once './IBuilder.php';
abstract class TraitementFile implements IBuilder
{
    protected $nameFile;
    protected $path ;
    protected $aFormations ;

    public function setContentFile($sContent) {
        $handle = fopen($this->path, "w+");
        fwrite($handle, $sContent);
        fclose($handle);
    }

    public  function getContentFile() {
        return json_decode(file_get_contents($this->path), true);
    }

    public function getTimeDateCreateFile() {
        if (file_exists($this->getPath())) {
            $sDate = date ("F d Y H:i:s.", filemtime($this->getPath()));
            $oDatetime = new DateTime($sDate);
            $oDatetime->modify('+2 hours');
            return "date de récupération du ficher : " .$oDatetime->format('F d Y H:i:s.') ;
        }
        return "error de ficher";
    }

    /**
     * @return array|mixed
     */
    public function getFormations()
    {
        return $this->aFormations;
    }

    /**
     * @return mixed
     */
    public function getNameFile()
    {
        return $this->nameFile;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

}