
<?php

date_default_timezone_set('UTC');

#usage:
//$r = new HTTPRequest('https://accueil-ent2.univ-avignon.fr/edt/exportAgendaUrl?uidnumber=1703634');
//$r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M1IL,%20groupe%20M1IL-Alt%20--%20UAPV.ics');
$r = new HTTPRequest('https://partage.univ-avignon.fr/home/franck.boue@alumni.univ-avignon.fr/Emploi%20du%20temps%20de%202-M1RI,%20groupe%20M1RI-Alt%20--%20UAPV.ics');
$sCalendar = $r->DownloadToString();




$handle = fopen("./calendar.txt", "w+");

fwrite($handle, $sCalendar);

fclose($handle);

/*Ouverture du fichier en lecture seule*/
$handle = fopen("./calendar.txt", "r");
$sCalendar ='';
/*Si on a réussi à ouvrir le fichier*/
if ($handle)
{
    /*Tant que l'on est pas à la fin du fichier*/
    while (!feof($handle))
    {
        /*On lit la ligne courante*/
        $buffer = fgets($handle);
        /*On l'affiche*/

        $sCalendar .= $buffer;
    }
    /*On ferme le fichier*/
    fclose($handle);
}

$re = '/BEGIN:VEVENT(?:.|\n)*?.END:VEVENT/smu';

$aEvents =[];
$i = 1;



foreach (explode('BEGIN:VEVENT',$sCalendar) as $val) {

    if ($i != 1) {

//        var_dump($val);
//        var_dump(preg_match("/Férié/i",$val));
        if (preg_match("/Férié/i",$val) === 0) {


//            var_dump(explode('- ', $val));
            $oEvent = new Events;

            /* date start */
            $stransDateStart = explode("\n", stristr($val, 'DTSTART'))[0];
            $stransDateStart = str_replace('DTSTART:', '', $stransDateStart);
            $stransDateStart = str_replace('T', '', $stransDateStart);
            $stransDateStart = str_replace('Z', '', $stransDateStart);
            $dateStart = DateTime::createFromFormat('YmdHis', trim($stransDateStart));
//        // fix bug : de l'export de url les heurs arrive avec  2heur en moins
            $dateStart->modify('+2 hours');
            $oEvent->start = str_replace('UTC', 'T', $dateStart->format('Y-m-dTH:i:s'));


            $stransDateEnd = explode("\n", stristr($val, 'DTEND'))[0];
            $stransDateEnd = str_replace('DTEND:', '', $stransDateEnd);
            $stransDateEnd = str_replace('T', '', $stransDateEnd);
            $stransDateEnd = str_replace('Z', '', $stransDateEnd);
            $dateEnd = DateTime::createFromFormat('YmdHis', trim($stransDateEnd));
//        // fix bug : de l'export de url les heurs arrive avec  2heur en moins
            $dateEnd->modify('+2 hours');
            $oEvent->end = str_replace('UTC', 'T', $dateEnd->format('Y-m-dTH:i:s'));

            /* salle */
            $sSalle = '';
            $sSalle = explode("\n", stristr($val, 'LOCATION:'))[0];
            $sSalle = str_replace('LOCATION:', '', $sSalle);


//        /* matiere */
//        $sMatiere='';
//        $sMatiere= explode("\n",stristr($val, 'Matière :'))[0];
//
//
//        /* quand pas matière ex : réservation de salle */
//        if (key_exists(1,explode(" - ",$sMatiere)) === TRUE) {
//            $sMatiere = stristr(explode(" - ", $sMatiere)[1], '\nEnseignan', true);
//        } else {
//            $sMatiere = stristr($sMatiere, '\nEnseignan', true);
//        }


            $oEvent->title = 'salle:' . $sSalle . ' ' . str_replace('SUMMARY:', '', stristr(stristr($val, 'SUMMARY:'), 'DESCRIPTION:', true));
            array_push($aEvents, $oEvent);
        }
    }
    $i++;
}




$jEvents = json_encode($aEvents);
//$jEvents = $aEvents;


class Events
{
    public $title = '';
    public $start = '';
    public $end = '';
}




class HTTPRequest
{
    var $_fp;        // HTTP socket
    var $_url;        // full URL
    var $_host;        // HTTP host
    var $_protocol;    // protocol (HTTP/HTTPS)
    var $_uri;        // request URI
    var $_port;        // port

    // scan url
    function _scan_url()
    {
        $req = $this->_url;

        $pos = strpos($req, '://');
        $this->_protocol = strtolower(substr($req, 0, $pos));

        $req = substr($req, $pos+3);
        $pos = strpos($req, '/');
        if($pos === false)
            $pos = strlen($req);
        $host = substr($req, 0, $pos);

        if(strpos($host, ':') !== false)
        {
            list($this->_host, $this->_port) = explode(':', $host);
        }
        else
        {
            $this->_host = $host;
            $this->_port = ($this->_protocol == 'https') ? 443 : 80;
        }

        $this->_uri = substr($req, $pos);
        if($this->_uri == '')
            $this->_uri = '/';
    }

    // constructor
    function HTTPRequest($url)
    {
        $this->_url = $url;
        $this->_scan_url();
    }

    // download URL to string
    function DownloadToString()
    {
        $crlf = "\r\n";
        $response= '';
        // generate request
        $req = 'GET ' . $this->_uri . ' HTTP/1.0' . $crlf
            .    'Host: ' . $this->_host . $crlf
            .    $crlf;

        // fetch
        $this->_fp = fsockopen(($this->_protocol == 'https' ? 'ssl://' : '') . $this->_host, $this->_port);
        fwrite($this->_fp, $req);
        while(is_resource($this->_fp) && $this->_fp && !feof($this->_fp))
            $response .= fread($this->_fp, 1024);
        fclose($this->_fp);

        // split header and body
        $pos = strpos($response, $crlf . $crlf);
        if($pos === false)
            return($response);
        $header = substr($response, 0, $pos);
        $body = substr($response, $pos + 2 * strlen($crlf));

        // parse headers
        $headers = array();
        $lines = explode($crlf, $header);
        foreach($lines as $line)
            if(($pos = strpos($line, ':')) !== false)
                $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));

        // redirection?
        if(isset($headers['location']))
        {
            $http = new HTTPRequest($headers['location']);
            return($http->DownloadToString($http));
        }
        else
        {
            return($body);
        }
    }
}


//todo : faire un ficher temporaire




?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome.min.css" >
    <link rel='stylesheet' href='./css/fullcalendar.min.css' />
    <link rel='stylesheet' href='./css/jquery.qtip.min.css' />
    <link rel="stylesheet" href="./css/chosen.css">
    <!--      version + récente-->

    <style type="text/css" media="all">
        /* fix rtl for demo */
        .chosen-rtl .chosen-drop { left: -9000px; }


        #top,
        #calendar.fc-unthemed {
            font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
        }

        #top {
            background: #eee;
            border-bottom: 1px solid #ddd;
            padding: 0 10px;
            line-height: 40px;
            font-size: 12px;
            color: #000;
        }

        #top .selector {
            display: inline-block;
            margin-right: 10px;
        }

        #top select {
            font: inherit; /* mock what Boostrap does, don't compete  */
        }

        .left { float: left }
        .right { float: right }
        .clear { clear: both }

        #calendar {
            max-width: 1000px;
            margin: 50px auto;
        }

    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>


<div id="top">


    <div class="left">
        <div>
            <form id="formFormation">
                <select id="selectFormation" data-placeholder="choisir votre formation & groupe" class="chosen-select">
                    <option value=""></option>
                    <optgroup label="M1 - ILSEN">
                        <option>classique ILSEN</option>
                        <option>alternant ILSEN</option>
                    </optgroup>
                    <optgroup label="M1 - RISM">
                        <option>classique RISM</option>
                        <option>alternant RISM</option>
                    </optgroup>
                </select>
            </form>
        </div>

    </div>
    <div class="right">

        <div id="theme-system-selector" class="selector">
            Theme System:

            <select>
                <option value="bootstrap3" selected="">Bootstrap 3</option>
                <option value="jquery-ui">jQuery UI</option>
                <option value="standard">unthemed</option>
            </select>
        </div>

        <div data-theme-system="bootstrap3" class="selector" style="">
            Theme Name:

            <select>
                <option value="">Default</option>
                <option value="cosmo">Cosmo</option>
                <option value="cyborg">Cyborg</option>
                <option value="darkly">Darkly</option>
                <option value="flatly">Flatly</option>
                <option value="journal" selected="">Journal</option>
                <option value="lumen">Lumen</option>
                <option value="paper">Paper</option>
                <option value="readable">Readable</option>
                <option value="sandstone">Sandstone</option>
                <option value="simplex">Simplex</option>
                <option value="slate">Slate</option>
                <option value="solar">Solar</option>
                <option value="spacelab">Spacelab</option>
                <option value="superhero">Superhero</option>
                <option value="united">United</option>
                <option value="yeti">Yeti</option>
            </select>
        </div>

        <div data-theme-system="jquery-ui" class="selector" style="display: none;">
            Theme Name:

            <select>
                <option value="black-tie">Black Tie</option>
                <option value="blitzer">Blitzer</option>
                <option value="cupertino" selected="">Cupertino</option>
                <option value="dark-hive">Dark Hive</option>
                <option value="dot-luv">Dot Luv</option>
                <option value="eggplant">Eggplant</option>
                <option value="excite-bike">Excite Bike</option>
                <option value="flick">Flick</option>
                <option value="hot-sneaks">Hot Sneaks</option>
                <option value="humanity">Humanity</option>
                <option value="le-frog">Le Frog</option>
                <option value="mint-choc">Mint Choc</option>
                <option value="overcast">Overcast</option>
                <option value="pepper-grinder">Pepper Grinder</option>
                <option value="redmond">Redmond</option>
                <option value="smoothness">Smoothness</option>
                <option value="south-street">South Street</option>
                <option value="start">Start</option>
                <option value="sunny">Sunny</option>
                <option value="swanky-purse">Swanky Purse</option>
                <option value="trontastic">Trontastic</option>
                <option value="ui-darkness">UI Darkness</option>
                <option value="ui-lightness">UI Lightness</option>
                <option value="vader">Vader</option>
            </select>
        </div>


        <span id="loading" style="display: none;">loading theme...</span>



    </div>



    <div class="clear"></div>
</div>



<div class="container-fluid" style="margin-top: 50px;">
    <div class="row">

        <div id='calendar'></div>
    </div>
    <div


</body>

<script src="./js/jquery-3.1.1.min.js" type="text/javascript"></script>
<script src='./js/moment.min.js'></script>
<script src='./js/fullcalendar.min.js'></script>
<script src='./js/locale-all.js'></script>
<script src='./js/theme-chooser.js'></script>
<script src='./js/jquery.qtip.min.js'></script>
<script src="./js/chosen.jquery.js"></script>

<script type="text/javascript">
    var oData=<?PHP echo $jEvents;?>;

    console.log(oData);

    initThemeChooser({

        init: function(themeSystem) {
            $('#calendar').fullCalendar({
                themeSystem: themeSystem,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                }, // buttons for switching between views
                aspectRatio: 1.8,
                lang: 'fr',
                locale: 'fr',
                defaultView: 'agendaWeek',
                events: oData,
//        timeFormat: 'h:mm{ - h:mm}',
                allDaySlot: false,
                minTime: '08:00:00',
                maxTime: '20:00:00',
                weekends: false,
                eventRender: function(event, element) {
//                    console.log(event);
                    element.qtip({
                        content: event.title
                    });
                }
            });
        },

        change: function(themeSystem) {
            $('#calendar').fullCalendar('option', 'themeSystem', themeSystem);
        }
    });



        $("#selectFormation").chosen({no_results_text: "Oops, nothing found!"});
</script>
</html>


<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 12/09/2017
 * Time: 20:28
 */





