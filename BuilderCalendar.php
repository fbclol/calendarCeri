<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
include './vendor/autoload.php';
require_once './Event.php';
require_once './HelperFranck.php';
require_once './HTTPRequest.php';
require_once './BuilderFormation.php';
require_once './IBuilder.php';
require_once './TraitementFile.php';

class BuilderCalendar  extends TraitementFile implements IBuilder
{
    public $oBuilderFormation ;

    public function __construct($nameFile)
    {
        $this->nameFile = $nameFile;
        $this->path = "./event_cal_txt/calendar_" . $nameFile . ".txt";
        $this->oBuilderFormation = new BuilderFormation();
        $this->aFormations       = $this->oBuilderFormation->getFormations();
    }

    public  function vcalToJson() {
        return \Sabre\VObject\Reader::read(fopen($this->path, 'r'));
    }

    public function getEvents()
    {
        $oVcal = $this->vcalToJson();
        $aEventSSerialize = $oVcal->jsonSerialize()[2];
        $aEventsBis       = [];

        foreach ($aEventSSerialize as $key =>  $aEvents) {
            $aEvent = $aEvents[1];
            $oEvent = new Event();
            $oEvent->setTitle($aEvent[HelperFranck::array_find_deep($aEvent,"summary")[0]][3]);
            $oEvent->setStart($aEvent[HelperFranck::array_find_deep($aEvent,"dtstart")[0]][3]);
            $oEvent->setEnd($aEvent[HelperFranck::array_find_deep($aEvent,"dtend")[0]][3]);
            $oEvent->setType($oEvent->getTitle());
            if (empty(HelperFranck::array_find_deep($aEvent,"location"))) {
                $oEvent->setLocation("salle inconnue");
            }else {
                $oEvent->setLocation($aEvent[HelperFranck::array_find_deep($aEvent,"location")[0]][3]);
            }
            //Concaténation des mêmes cours à la suite + Détection d'un cours identique
            $sameEvent = false;
            foreach($aEventsBis as $e) {
                if( $e->getTitle() === $oEvent->getTitle() && $e->getLocation() === $oEvent->getLocation() &&
                    $e->getType() === $oEvent->getType() &&
                    (($e->getStart() <= $oEvent->getStart() && $oEvent->getStart() < $e->getEnd()))) {
                    $sameEvent = true;
                }
                if( $e->getTitle() === $oEvent->getTitle() && $e->getLocation() === $oEvent->getLocation() &&
                    $e->getType() === $oEvent->getType() &&
                    $e->getEnd() === $oEvent->getStart()) {
                    // On n'insère pas si le cours y est déjà
                    $e->setEnd($oEvent->getEnd()) ;
                    $sameEvent = true;
                }
            }
            if(!$sameEvent) {
                array_push($aEventsBis, $oEvent);
            }
        }
        return HelperFranck::object_to_array($aEventsBis);
    }

    /**
     * @return int
     */
    public function getEventsToSchedule()
    {
        $aEvents =  $this->getEvents();
        $ta=[];

        $a['type'] =  2;
        $a['size'] =  1;
        $a['fill'] =  true;
        $a['minimumSize'] =  0;
        $a['repeatCovers'] = true;
        $a['listTimes'] =  false;
        $a['eventsOutside'] =  false;
        $a['updateRows'] =  false;
        $a['updateColumns'] =  false;
        $a['around'] =  1543618800000;

        foreach ($aEvents as $aEvent) {

            $atest{'data'}['title']       = $aEvent['title'];
            $atest['data']['description'] = "frege";
            $atest['data']['location']    = $aEvent['location'];
            $atest['data']['color']       = "#1976d2";
            $atest['data']['forecolor']   = "#ffffff";
            $atest['data']['calendar']    = "ceri";
            $atest['data']['busy']        = "false";
            $atest['data']['icon']        = "card_travel";
            ;
            $dateStart = new DateTime($aEvent['start']);
            $dateEnd = new DateTime($aEvent['end']);


            $dteDiff     = $dateStart->diff($dateEnd);
            $dteDiffHeur = $dteDiff->format("%H");

            $dteDiffMinutes  = $dteDiff->format("%I");
            $dteDiffMinutes += $dteDiffHeur * 60;

            $atest['schedule']['times']        = [$dateStart->format("H:i")];
            $atest['schedule']['duration']     = $dteDiffMinutes;
            $atest['schedule']['durationUnit'] = "minutes";
            $atest['schedule']['dayOfMonth']   = [$dateStart->format("d")];
            $atest['schedule']['month']        = [$dateStart->format("m")];
            $atest['schedule']['year']         = [$dateStart->format("Y")];


            $ta["data"] = (object) $atest['data'];
            $ta["schedule"] = (object) $atest['schedule'];


            $a["events"][]=$ta;

        }


        return json_encode($a);
    }


    public function createCalendar()
    {
        try {
            $aUrlIndexName = array_column($this->aFormations, 'export_url','name');
            $r             = new HTTPRequest($aUrlIndexName[$this->nameFile]);
            $sCalendar     = $r->DownloadToString();
        } catch (Exception $e) {
            $sCalendar = null;
        }

        if ($sCalendar == "" || is_null($sCalendar)) {
            // uniquement l'ouverture du tmp
            $aEvents = $this->getEvents();
        } else {
            // écrire dans le un fichier puis ouverture
            $this->setContentFile($sCalendar);
            $aEvents = $this->getEvents();
        }
        return json_encode($aEvents);
    }

}