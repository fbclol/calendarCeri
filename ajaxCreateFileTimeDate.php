<?php

require_once './BuilderCalendar.php';
$oBuilderCalendar = new BuilderCalendar($_POST["formation"]);

echo $oBuilderCalendar->getTimeDateCreateFile();



