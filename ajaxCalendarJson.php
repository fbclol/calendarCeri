<?php
require_once './BuilderCalendar.php';
require_once './BuilderFormation.php';

if (!array_key_exists('formation', $_COOKIE) === false) {
    $oBuilderCalendar = new BuilderCalendar($_COOKIE['formation']);
} else {
    $oBuilderCalendar = new BuilderCalendar("m2-alt-ecom");
}
$jEvents           = $oBuilderCalendar->getEventsToSchedule();
$oBuilderFormation = $oBuilderCalendar->oBuilderFormation;
echo !empty($jEvents) ? $jEvents : '""';