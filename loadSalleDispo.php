<?php

require_once './HTTPRequest.php';

$r = new HTTPRequest('https://accueil-ent2.univ-avignon.fr/edt/loadSalleDispo?site='.$_POST["site"].'&date='.$_POST["date"].'&duree='.$_POST["duree"].'&debut='.$_POST["debut"].'');

$sloadSalle = $r->DownloadToString();
$nIdUniq = uniqid();
$handle = fopen("./loadSalle".$nIdUniq.".txt", "w+");
fwrite($handle, $sloadSalle);
fclose($handle);
/*Ouverture du fichier en lecture seule*/
$handle = fopen("./loadSalle".$nIdUniq.".txt", "r");
$sloadSalle = '';
/*Si on a réussi à ouvrir le fichier*/
if ($handle) {
    /*Tant que l'on est pas à la fin du fichier*/
    while (!feof($handle)) {
        /*On lit la ligne courante*/
        $buffer = fgets($handle);
        /*On l'affiche*/
        $sloadSalle .= $buffer;
    }
    /*On ferme le fichier*/
    fclose($handle);
}

echo $sloadSalle;
unlink('./loadSalle'.$nIdUniq.'.txt');

