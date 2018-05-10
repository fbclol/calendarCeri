
<?php

date_default_timezone_set('UTC');
require_once './Events.php';
require_once './HTTPRequest.php';


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
                $stransDateStart = str_replace('DTSTART:', '', $stransDateStart);
                $stransDateStart = str_replace('T', '', $stransDateStart);
                $stransDateStart = str_replace('Z', '', $stransDateStart);
                $dateStart = DateTime::createFromFormat('YmdHis', trim($stransDateStart));

//        // fix bug : de l'export de url les heurs arrive avec  2heur en moins
                //todo heur d'été et d'hiver
                if ($dateStart > $datetime1 === false) {
                    $dateStart->modify('+2 hours');
                } else {
                    $dateStart->modify('+1 hours');
                }

                $oEvent->start = str_replace('UTC', 'T', $dateStart->format('Y-m-dTH:i:s'));

                $stransDateEnd = explode("\n", stristr($val, 'DTEND'))[0];
                $stransDateEnd = str_replace('DTEND:', '', $stransDateEnd);
                $stransDateEnd = str_replace('T', '', $stransDateEnd);
                $stransDateEnd = str_replace('Z', '', $stransDateEnd);
                $dateEnd = DateTime::createFromFormat('YmdHis', trim($stransDateEnd));
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
                }

                $oEvent->type  = $sType;

                array_push($aEvents, $oEvent);
            }
        }
        $i++;
    }

    $jEvents = json_encode($aEvents);

}
//$jEvents = $aEvents;
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
    <link rel="stylesheet" href="./css/style_custom.css">
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
                        <option value="m1_cla_ilsen">M1 classique ILSEN</option>
                        <option value="m1_alt_ilsen">M1 alternant ILSEN</option>
                    </optgroup>
                    <optgroup label="M1 - RISM">
                        <option value="m1_cla_rism">M1 classique RISM</option>
                        <option value="m1_alt_rism">M1 alternant RISM</option>
                    </optgroup>
                    <optgroup label="M2 - ILSEN">
                        <option value="m2-alt-doc-emb">M2 alt doc emb</option>
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
</div>

<script src="./js/jquery-3.1.1.min.js"></script>
<script src='./js/moment.min.js'></script>
<script src='./js/fullcalendar.min.js'></script>
<script src='./js/locale-all.js'></script>
<script src='./js/theme-chooser.js'></script>
<script src='./js/jquery.qtip.min.js'></script>
<script src="./js/chosen.jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Votre code ici avec les appels à la fonction $()
        var oData = <?PHP echo !empty($jEvents) ? $jEvents : '""' ?>;
        var $sFormationTmp = <?PHP echo !empty($_COOKIE['formation']) ? '"' . $_COOKIE['formation'] . '"' : '""' ?>;
        bLegends = false;
        console.log(oData);

        initThemeChooser({
            init: function (themeSystem) {
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
                    eventRender: function (event, element) {
//                    console.log(event);
                        element.qtip({
                            content: event.title
                        });
                    },
                    eventAfterRender: function (event, element, view) {
                        var dataHoje = new Date();

                        console.log(event.type.toString());
                        if (event.start < dataHoje && event.end > dataHoje) {
                            //event.color = "#FFB347"; //Em andamento
                            element.css('background-color', '#FFB347');
                        } else if (event.start < dataHoje && event.end < dataHoje) {
                            //event.color = "#77DD77"; //Concluído OK
                            element.css('background-color', '#77DD77');
//                    } else if (event.start > dataHoje && event.end > dataHoje) {
//                        //event.color = "#AEC6CF"; //Não iniciado
////                       / element.css('background-color', '#AEC6CF');
                        } else if (event.type == 'Evaluation') {
                            element.css('background-color', '#E82C61');
                        } else if (event.type == 'tp') {
                            element.css('background-color', '#FFB373');
                        }
                    },
                    eventAfterAllRender : function (view) {

                        if (bLegends === false) {
                            var legends = '<span class="label label-tp">TP</span><span class="label label-evaluation">Evaluation</span><span class="label label-passe">passé</span><span class="label label-normal">normal</span>';
                            $('.fc-left').append($(legends));
                            bLegends = true;
                        }
                    }
                });
            },

            change: function (themeSystem) {
                $('#calendar').fullCalendar('option', 'themeSystem', themeSystem);
                var legends = '<span class="label label-tp">TP</span><span class="label label-evaluation">Evaluation</span><span class="label label-passe">passé</span><span class="label label-normal">normal</span>';
                $('.fc-left').append($(legends));
            }
        });



        if ($sFormationTmp !== '') {
            $('#selectFormation option[value=' + $sFormationTmp + ']').attr("selected", "selected");
        }
        $("#selectFormation").chosen({no_results_text: "Oops, nothing found!"});


        $('#selectFormation').on('change', function (evt, params) {
//        console.log(params.selected.toString());

            $.ajax({
                url: "formationAjax.php",
//            data: "formation="+params.selected,
                data: {formation: $('#selectFormation option:selected').val()},
                type: "POST",
                dataType: 'html',
                success: function (e) {


                    console.log(JSON.stringify(e));
                    window.location.reload();
                },
                error: function (e) {
                    console.log(JSON.stringify(e));
                }
            });
        });
    });
</script>
</body>
</html>


<?php
/**
 * Created by PhpStorm.
 * User: franck
 * Date: 12/09/2017
 * Time: 20:28
 */





