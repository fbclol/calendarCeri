<?php

require_once './HelperFranck.php';
require_once './HTTPRequest.php';
require_once './BuilderFormation.php';
require_once './IBuilder.php';
require_once './TraitementFile.php';

class BuilderSalleDispo extends TraitementFile implements IBuilder
{
    const URL_SALLE_DISPO ="https://accueil-ent2.univ-avignon.fr/edt/loadSalleDispo";

    private $sSite;
    private $sDate;
    private $sDuree;
    private $sDebut;
    private $urlComplete;

    function __construct($sSite,$sDate,$sDuree,$sDebut)
    {
        $this->sSite = $sSite;
        $this->sDate = $sDate;
        $this->sDuree = $sDuree;
        $this->sDebut = $sDebut;
        $this->urlComplete = self::constructUrl();
    }

    public function constructUrl() {

        $url           = self::URL_SALLE_DISPO;
        $data["site"]  = $this->sSite;
        $data["date"]  = $this->sDate;
        $data["duree"] = $this->sDuree;
        $data["debut"] = $this->sDebut;

        return $url."?".http_build_query($data);
    }

    public function getSalleDispo()
    {
        $r          = new HTTPRequest($this->urlComplete);
        $sloadSalle = $r->DownloadToString();
        return $sloadSalle;
    }
}