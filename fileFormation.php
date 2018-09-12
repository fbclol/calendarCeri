<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 12/09/2018
 * Time: 15:16
 */


$filename = "./calendar".$_POST["formation"].".txt";
if (file_exists($filename)) {
    echo "date de récupération du calendrier : " . date ("F d Y H:i:s.", filemtime($filename));
}

