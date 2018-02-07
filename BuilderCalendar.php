<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 16/12/2017
 * Time: 12:02
 */
date_default_timezone_set('UTC');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
require_once './Events.php';
require_once './HTTPRequest.php';
class BuilderCalendar
{

    /**
     * @return string
     */
    public static function createCalendar(){
       #usage:
       if (array_key_exists('formation',$_COOKIE) === false) {
           // default
//    setcookie("theme", 'journal');
//    setcookie("formation", 'm1_alt_ilsen');
//    $_SESSION['theme']     = 'journal';
//    $_SESSION['formation'] = 'm1_ilsen_alt';
       } else {
           switch ($_COOKIE['formation']) {
               case 'm1_alt_ilsen' :
                   $r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M1IL,%20groupe%20M1IL-Alt%20--%20UAPV.ics');
                   break;
               case 'm1_alt_rism':
                   $r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M1RI,%20groupe%20M1RI-Alt%20--%20UAPV.ics');
                   break;
               case 'm1_cla_ilsen':
                   $r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M1IL,%20groupe%20M1IL-Cla%20--%20UAPV.ics');
                   break;
               case 'm1_cla_rism':
                   $r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M1RI,%20groupe%20M1RI-Cla1%20--%20UAPV.ics');
                   break;
               case 'm2-alt-doc-emb':
                   $r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M2IL,%20groupes%20M2-AppliEmb-alt,%20M2-DevEmb%20Appli,%20M2-ecom,%20M2-Ing%C3%A9Doc,%20M2IL-Alt%20--%20UAPV.ics');
                   break;
               default:
                   $r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M1IL,%20groupe%20M1IL-Alt%20--%20UAPV.ics');
           }
           $sCalendar = $r->DownloadToString();
           $nIdUniq = uniqid();
           $handle = fopen("./calendar".$nIdUniq.".txt", "w+");
           fwrite($handle, $sCalendar);
           fclose($handle);
           /*Ouverture du fichier en lecture seule*/
           $handle = fopen("./calendar".$nIdUniq.".txt", "r");
           $sCalendar = '';
           /*Si on a réussi à ouvrir le fichier*/
           if ($handle) {
               /*Tant que l'on est pas à la fin du fichier*/
               while (!feof($handle)) {
                   /*On lit la ligne courante*/
                   $buffer = fgets($handle);
                   /*On l'affiche*/
                   $sCalendar .= $buffer;
               }
               /*On ferme le fichier*/
               fclose($handle);
           }
           unlink('./calendar'.$nIdUniq.'.txt');
           $aEvents = [];
           $i = 1;
           $datetime1 = new DateTime('2017-10-29');
           foreach (explode('BEGIN:VEVENT', $sCalendar) as $val) {
               if ($i != 1) {
//        var_dump($val);
                   if (preg_match("/Férié/i", $val) === 0) {
//            var_dump(explode('- ', $val));
                       $oEvent = new Events;
                       /* date start */
                       $stransDateStart = explode("\n", stristr($val, 'DTSTART'))[0];
                    //   var_dump($stransDateStart);
                       // if c'est un dtstart avec ; au lieu de :
                       if (strstr($stransDateStart, 'DTSTART:') !== false) {
                           $stransDateStart = str_replace('DTSTART:', '', $stransDateStart);
                           $stransDateStart = str_replace('T', '', $stransDateStart);
                           $stransDateStart = str_replace('Z', '', $stransDateStart);
                           $dateStart = DateTime::createFromFormat('YmdHis', trim($stransDateStart));
                       } else {
                           $stransDateStart = str_replace('DTSTART;VALUE=DATE:', '', $stransDateStart);
                           $dateStart = DateTime::createFromFormat('Ymd', trim($stransDateStart));
                       }
                       //var_dump($stransDateStart);

                       //var_dump($dateStart);
//        // fix bug : de l'export de url les heurs arrive avec  2heur en moins
                       //todo heur d'été et d'hiver
                       if ($dateStart > $datetime1 === false) {
                          // var_dump(explode("\n", stristr($val, 'DTSTART'))[0]);
                           $dateStart->modify('+2 hours');
                       } else {
                           $dateStart->modify('+1 hours');
                       }
                       $oEvent->start = str_replace('UTC', 'T', $dateStart->format('Y-m-dTH:i:s'));
                       $stransDateEnd = explode("\n", stristr($val, 'DTEND'))[0];
                       if (strstr($stransDateEnd, 'DTEND:') !== false) {
                           $stransDateEnd = str_replace('DTEND:', '', $stransDateEnd);
                           $stransDateEnd = str_replace('T', '', $stransDateEnd);
                           $stransDateEnd = str_replace('Z', '', $stransDateEnd);
                           $dateEnd = DateTime::createFromFormat('YmdHis', trim($stransDateEnd));
                       }else {
                           $stransDateEnd = str_replace('DTEND;VALUE=DATE:', '', $stransDateEnd);
                           $dateEnd = DateTime::createFromFormat('Ymd', trim($stransDateEnd));
                       }
//        // fix bug : de l'export de url les heurs arrive avec  2heur en moins
                       //todo heur d'été et d'hiver
                       if ($dateEnd > $datetime1 === false) {
                           $dateEnd->modify('+2 hours');
                       } else {
                           $dateEnd->modify('+1 hours');
                       }
                       $oEvent->end = str_replace('UTC', 'T', $dateEnd->format('Y-m-dTH:i:s'));
                       /* salle */
                       $sSalle = '';
                       $sSalle = explode("\n", stristr($val, 'LOCATION:'))[0];
                       $sSalle = str_replace('LOCATION:', '', $sSalle);
                       $oEvent->title = 'salle:' . $sSalle . ' ' . str_replace('SUMMARY:', '', stristr(stristr($val, 'SUMMARY:'), 'DESCRIPTION:', true));
                       /* type */
                       $sType='';
                       if (preg_match("/Evaluation/i", $val) === 1) {
                           $sType = 'Evaluation';
                       } else if (preg_match("/tp/i",$val) === 1) {
                           $sType = 'tp';
                       } else if (preg_match("/ANGLAIS /i",$val) === 1) {
                           $sType = 'anglais';
                       } else  {
                           $sType = "";
                       }
                       $oEvent->type  = $sType;
                       // Concaténation des mêmes cours à la suite + Détection d'un cours identique
                       $sameEvent = false;
                       foreach($aEvents as $e) {
                           if( $e->title === $oEvent->title &&
                               $e->type === $oEvent->type &&
                               (($e->start <= $oEvent->start && $oEvent->start < $e->end))) {
                               $sameEvent = true;
                           }
                           if( $e->title === $oEvent->title &&
                               $e->type === $oEvent->type &&
                               $e->end === $oEvent->start) {
                               // On n'insère pas si le cours y est déjà
                               $e->end = $oEvent->end;
                               $sameEvent = true;
                           }
                       }
                       if(!$sameEvent) {
                           array_push($aEvents, $oEvent);
                       }
                   }
               }
               $i++;
           }
          // $jEvents = json_encode($aEvents);
           return json_encode($aEvents);
       }
   }

}