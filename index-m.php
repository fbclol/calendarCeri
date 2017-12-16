<?php
/**
 * Created by PhpStorm.
 * User: remid
 * Date: 09/10/2017
 * Time: 11:57
 */

require_once './BuilderCalendar.php';

$jEvents = BuilderCalendar::createCalendar();
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
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
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

<div class="container-fluid" style="margin-top: 20px;">
    <div class="row">

        <div id='calendar' class='col-lg-10 col-lg-offset-1 col-sm-12'></div>
    </div>
</div>

<script src="./js/jquery-3.1.1.min.js"></script>
<script src='./js/moment.min.js'></script>
<script src='./js/fullcalendar.min.js'></script>
<script src='./js/locale-all.js'></script>
<script src='./js/theme-chooser.js'></script>
<script src='./js/jquery.qtip.min.js'></script>
<script src="./js/chosen.jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
                    maxTime: '19:30:00',
                    weekends: false,
                    eventRender: function(event, element) {
                        $(element).tooltip({title: event.title, placement:'bottom'});
                    },
                    eventAfterRender: function (event, element, view) {
                        var dateEte = new Date('2017-10-29');

                        var now = new Date();
                        if(now < dateEte) {now.setTime(now.getTime() + 2*60*60*1000);}
                        else {now.setTime(now.getTime() + 1*60*60*1000);}
                        // Evenement en cours
                        if (event.start < now && now < event.end) {element.addClass('encours');}
                        // Evenement passé
                        else if (event.start < now) {element.addClass('passe');}
                        // Partiel
                        else if (event.type === 'Evaluation') {element.addClass('eval');}
                        // TP
                        else if (event.type === 'tp') {element.addClass('tp');}
                        // TD - CM
                        else {element.addClass('td-cm');}
                    },
                    eventAfterAllRender : function (view) {
                        if (bLegends === false) {
                            var legends = '<span class="label td-cm">CM/TD</span><span class="label tp">TP</span><span class="label eval">Evaluation</span><span class="label encours">En cours</span><span class="label passe">passé</span>';
                            $('.fc-left').append($(legends));
                            bLegends = true;
                        }
                    }
                });
            },
            change: function (themeSystem) {
                $('#calendar').fullCalendar('option', 'themeSystem', themeSystem);
                var legends = '<span class="label td-cm">CM/TD</span><span class="label tp">TP</span><span class="label eval">Evaluation</span><span class="label encours">En cours</span><span class="label passe">passé</span>';
                $('.fc-left').append($(legends));
            }
        });
        // On ajuste la hauteur du calendar
        var calendarHeight = $(window).height() - $("#top").outerHeight();
        $('#calendar').fullCalendar('option', 'contentHeight', calendarHeight);
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