<?php


require_once './BuilderSalleDispo.php';
$oBuilderSalleDispo = new BuilderSalleDispo($_POST["site"],$_POST["date"],$_POST["duree"],$_POST["debut"]);
echo $oBuilderSalleDispo->getSalleDispo();