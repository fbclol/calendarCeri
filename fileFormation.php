<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 12/09/2018
 * Time: 15:16
 */


$filename = "./event_cal_txt/calendar_".$_POST["formation"].".txt";
if (file_exists($filename)) {
   $date = date ("F d Y H:i:s.", filemtime($filename));
    $datetime1 = new DateTime($date);
    $datetime1->modify('+2 hours');
    echo "date de récupération du calendrier : " .$datetime1->format('F d Y H:i:s.') ;
}

