<?php

date_default_timezone_set('UTC');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
require_once './Events.php';
require_once './HTTPRequest.php';
require_once './BuilderFormation.php';

class BuilderCalendar
{
    public $oBuilderFormation ;
    public $aFormations ;

    public function __construct($name)
    {
        $this->oBuilderFormation = new BuilderFormation($name);
        $this->aFormations       = $this->oBuilderFormation->aFormations;
    }

    public static function setContentFile($sFormation,$sCalendar) {
        $nIdUniq = uniqid();
        $handle = fopen("./event_cal_txt/calendar_" . $sFormation . ".txt", "w+");
        fwrite($handle, $sCalendar);
        fclose($handle);
    }

    public static function getContentFile($sFormation) {
        /*Ouverture du fichier en lecture seule*/
        $handle = fopen("./event_cal_txt/calendar_".$sFormation.".txt", "r");
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
        return $sCalendar;
    }


    public static function getEvents($sFormation) {

        $sCalendar =  self::getContentFile($sFormation);
        //unlink('./calendar'.$sFormation.'.txt');
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
                        $dateStart->modify('+2 hours');
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
                        $dateEnd->modify('+2 hours');
                    }
                    $oEvent->end = str_replace('UTC', 'T', $dateEnd->format('Y-m-dTH:i:s'));
                    /* salle */
                    $sSalle = '';
                    $sSalle = explode("\n", stristr($val, 'LOCATION;LANGUAGE=fr:'))[0];

                    $sSalle = str_replace('LOCATION;LANGUAGE=fr:', '', $sSalle);

                    if (!$sSalle) {
                        $sSalle = "salle inconnue";
                        $oEvent->title = 'salle:' . $sSalle . ' ' . str_replace('SUMMARY;LANGUAGE=fr:', '', stristr(stristr($val, 'SUMMARY;LANGUAGE=fr:'), 'DESCRIPTION;LANGUAGE=fr:', true));
                    } else {
                        $oEvent->title = 'salle:' . $sSalle . ' ' . str_replace('SUMMARY;LANGUAGE=fr:', '', stristr(stristr($val, 'SUMMARY;LANGUAGE=fr:'), 'LOCATION;LANGUAGE=fr:', true));
                    }
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
        return $aEvents;
    }

    public  function createCalendar()
    {
        if (!array_key_exists('formation', $_COOKIE) === false) {
            try {
                $aUrlIndexName = array_column($this->aFormations, 'export_url','name');
                $r             = new HTTPRequest($aUrlIndexName[$_COOKIE['formation']]);
                $sCalendar     = $r->DownloadToString();
            } catch (Exception $e) {
                $sCalendar = null;
            }

            if ($sCalendar == "" || is_null($sCalendar)) {
                // uniquement l'ouverture du tmp
                $aEvents = self::getEvents($_COOKIE['formation']);
            } else {
                // écrire dans le un fichier puis ouverture
                self::setContentFile($_COOKIE['formation'],$sCalendar);
                $aEvents = self::getEvents($_COOKIE['formation']);
            }
            return json_encode($aEvents);
        } else {
            try {
                $aUrlIndexName = array_column($this->aFormations, 'export_url','name');
                $r             = new HTTPRequest($aUrlIndexName["m2-alt-ecom"]);
                $sCalendar     = $r->DownloadToString();
            } catch (Exception $e) {
                $sCalendar = null;
            }

            if ($sCalendar == "" || is_null($sCalendar)) {
                // uniquement l'ouverture du tmp
                $aEvents = self::getEvents($_COOKIE['formation']);
            } else {
                // écrire dans le un fichier puis ouverture
                self::setContentFile($_COOKIE['formation'],$sCalendar);
                $aEvents = self::getEvents($_COOKIE['formation']);
            }
            return json_encode($aEvents);
        }
    }
}


