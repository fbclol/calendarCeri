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
    <link rel="stylesheet" href="css/datepicker.css">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body>

<div id="top">
    <div class="left">
        <div>
            <form id="formFormation">
                <select id="selectFormation" data-placeholder="choisir votre formation & groupe" class="chosen-select">
                    <option value=""></option>
                    <optgroup label="L3 - INFORMATIQUE">
                        <option value="l3_cla1">L3 info alternant td1</option>
                        <option value="l3_cla2">L3 info alternant td2</option>
                        <option value="l3_cla3">L3 info alternant td3</option>
                        <option value="l3_cla4">L3 info alternant td4</option>
                        <option value="l3_alt5">L3 info alternant grp5</option>
                        <option value="l3_alt6">L3 info alternant grp6</option>

                    </optgroup>
                    <optgroup label="M1 - ILSEN">
                        <option value="m1_cla_ilsen">M1 classique ILSEN</option>
                        <option value="m1_alt_ilsen">M1 alternant ILSEN</option>
                    </optgroup>
                    <optgroup label="M1 - RISM">
                        <option value="m1_cla_sicom">M1 classique SICOM</option>
                        <option value="m1_alt_sicom">M1 alternant SICOM</option>
                    </optgroup>
                    <optgroup label="M2 - ILSEN">
                        <option value="m2-alt-doc-emb">M2 e-com alternant</option>
                        <option value="m2-cla-doc-emb">M2 e-com classique</option>
                        <option value="m2-alt-ingedoc">M2 ingedoc alternant</option>
                        <option value="m2-cla-ingedoc">M2 ingedoc classique</option>
                    </optgroup>
                    <optgroup label="M2 - SICOM">
                        <option value="m2-alt-multi">M2 multi alternant</option>
                    </optgroup>
                </select>
            </form>
        </div>
    </div>
    <div class="right text-center">
            <div class="col-md-2">
                <span class="label label-warning">Salle dispo</span>
                <a id="buttonAfficherSalle">Afficher</a>
            </div>
            <div class="col-md-3">
                <select id="nomSite">
                    <option value="CERI" class="optionSalle">CERI</option>
                    <option value="Agrosciences" class="optionSalle">Agrosciences</option>
                    <option value="Centre-ville" class="optionSalle">Centre-ville</option>
                    <option value="IUT" class="optionSalle">IUT</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="datepicker" class="hasDatepicker">
            </div>
            <div class="col-md-2">
                <select id="debut">
                    <option value="8">8h</option>
                    <option value="8.30">8h30</option>
                    <option value="9">9h</option>
                    <option value="9.30">9h30</option>
                    <option value="10">10h</option>
                    <option value="10.30">10h30</option>
                    <option value="11">11h</option>
                    <option value="11.30">11h30</option>
                    <option value="12">12h</option>
                    <option value="12.30">12h30</option>
                    <option value="13">13h</option>
                    <option value="13.30">13h30</option>
                    <option value="14">14h</option>
                    <option value="14.30">14h30</option>
                    <option value="15">15h</option>
                    <option value="15.30">15h30</option>
                    <option value="16">16h</option>
                    <option value="16.30">16h30</option>
                    <option value="17">17h</option>
                    <option value="17.30">17h30</option>
                    <option value="18">18h</option>
                    <option value="18.30">18h30</option>
                    <option value="19">19h</option>
                    <option value="19.30">19h30</option>
                </select>
            </div>
            <div class="col-md-2">
                <select id="duree">
                    <option value="1.5">1h30</option>
                    <option value="3">3h00</option>
                    <option value="4.5">4h30</option>
                    <option value="6">6h00</option>
                </select>
            </div>









        <!--        <div id="theme-system-selector" class="selector">-->
        <!--            Theme System:-->
        <!--            <select>-->
        <!--                <option value="bootstrap3" selected="">Bootstrap 3</option>-->
        <!--                <option value="jquery-ui">jQuery UI</option>-->
        <!--                <option value="standard">unthemed</option>-->
        <!--            </select>-->
        <!--        </div>-->

        <!--        <div data-theme-system="bootstrap3" class="selector" style="">-->
        <!--            Theme Name:-->
        <!---->
        <!--            <select>-->
        <!--                <option value="">Default</option>-->
        <!--                <option value="cosmo">Cosmo</option>-->
        <!--                <option value="cyborg">Cyborg</option>-->
        <!--                <option value="darkly">Darkly</option>-->
        <!--                <option value="flatly">Flatly</option>-->
        <!--                <option value="journal" selected="">Journal</option>-->
        <!--                <option value="lumen">Lumen</option>-->
        <!--                <option value="paper">Paper</option>-->
        <!--                <option value="readable">Readable</option>-->
        <!--                <option value="sandstone">Sandstone</option>-->
        <!--                <option value="simplex">Simplex</option>-->
        <!--                <option value="slate">Slate</option>-->
        <!--                <option value="solar">Solar</option>-->
        <!--                <option value="spacelab">Spacelab</option>-->
        <!--                <option value="superhero">Superhero</option>-->
        <!--                <option value="united">United</option>-->
        <!--                <option value="yeti">Yeti</option>-->
        <!--            </select>-->
        <!--        </div>-->
        <!---->
        <!--        <div data-theme-system="jquery-ui" class="selector" style="display: none;">-->
        <!--            Theme Name:-->
        <!---->
        <!--            <select>-->
        <!--                <option value="black-tie">Black Tie</option>-->
        <!--                <option value="blitzer">Blitzer</option>-->
        <!--                <option value="cupertino" selected="">Cupertino</option>-->
        <!--                <option value="dark-hive">Dark Hive</option>-->
        <!--                <option value="dot-luv">Dot Luv</option>-->
        <!--                <option value="eggplant">Eggplant</option>-->
        <!--                <option value="excite-bike">Excite Bike</option>-->
        <!--                <option value="flick">Flick</option>-->
        <!--                <option value="hot-sneaks">Hot Sneaks</option>-->
        <!--                <option value="humanity">Humanity</option>-->
        <!--                <option value="le-frog">Le Frog</option>-->
        <!--                <option value="mint-choc">Mint Choc</option>-->
        <!--                <option value="overcast">Overcast</option>-->
        <!--                <option value="pepper-grinder">Pepper Grinder</option>-->
        <!--                <option value="redmond">Redmond</option>-->
        <!--                <option value="smoothness">Smoothness</option>-->
        <!--                <option value="south-street">South Street</option>-->
        <!--                <option value="start">Start</option>-->
        <!--                <option value="sunny">Sunny</option>-->
        <!--                <option value="swanky-purse">Swanky Purse</option>-->
        <!--                <option value="trontastic">Trontastic</option>-->
        <!--                <option value="ui-darkness">UI Darkness</option>-->
        <!--                <option value="ui-lightness">UI Lightness</option>-->
        <!--                <option value="vader">Vader</option>-->
        <!--            </select>-->
        <!--        </div>-->
        <!--        <span id="loading" style="display: none;">loading theme...</span>-->
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
<script src="./js/bootstrap-datepicker.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="./js/sweetalert2.all.min.js"></script>
<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
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
                        // anglais
                        else if (event.type === 'anglais') {element.addClass('cm-anglais');}
                        // cm
                        else {element.addClass('td-cm'); }
                    },
                    eventAfterAllRender : function (view) {
                        if (bLegends === false) {
                            var legends = '<span class="label cm-anglais">Anglais</span><span class="label td-cm">CM/TD</span><span class="label tp">TP</span><span class="label eval">Evaluation</span><span class="label encours">En cours</span><span class="label passe">passé</span>';
                            $('.fc-left').append($(legends));
                            bLegends = true;
                        }
                    }
                });
            },
            change: function (themeSystem) {
                $('#calendar').fullCalendar('option', 'themeSystem', themeSystem);
                var legends = '<span class="label cm-anglais">Anglais</span><span class="label td-cm">CM/TD</span><span class="label tp">TP</span><span class="label eval">Evaluation</span><span class="label encours">En cours</span><span class="label passe">passé</span>';
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


        $('#datepicker').datepicker();
        if ($('#datepicker').datepicker().val() == '') {
            var now = new Date();
            day = now.getDate();
            month = now.getMonth()+1;
            year = now.getFullYear();

            if (month.toString().length === 1) {
                month = '0' + month;
            }
            if (day.toString().length === 1) {
                day = '0' + day;
            }

            $('#datepicker').datepicker().val(month+"/"+day+"/"+year);
            date = day+'-'+month+'-'+year;
        }
        // Click sur une salle
        $('body').on('click', '#buttonAfficherSalle', function(event){

            event.preventDefault();
            // On appelle une fonction ajax pour connaitre la liste des salles disponibles
            var site = $('#nomSite').find('option:selected').attr('value');
            var duree = $('#duree').find('option:selected').attr('value');
            var debut = $('#debut').find('option:selected').attr('value');
            var month = $('#datepicker').datepicker().val().slice( 0,2 );
            var day = $('#datepicker').datepicker().val().slice( 3 ,-5);
            var year = $('#datepicker').datepicker().val().slice( 6 );
            var date = day+'-'+month+'-'+year;



            $.ajax({
                type: 'POST',
                crossDomain: true,
                dataType: 'json',
                url: 'loadSalleDispo.php',
                data: { site: site, date: date, duree: duree, debut: debut },
                success: function(data) {
                    console.log(data);
                    htmlTtile = "<div style='margin-top: 30px;' id='results'><h4 style='color: #4c4741;'>Salle(s) disponible(s) au "+site+" pour les critères choisis :</h4>";
                    html ='';
                    jQuery.each( data, function( i,val ) {
                        html += "<p><i class=\"fas fa-calendar-check\"></i> <span class='label td-cm'>"+val+"</span></p>";
                    });
                    html += '</div>';
                    console.log(html);
                    console.log(data);

                    swal({
                        title: htmlTtile,
                        html: $('<div>').addClass('some-class').append(html),
                        type: 'info'
                    })
                },
                error: function(data) { console.log(data); }
            }).done(function( data ) {


            });
        });



    });
</script>
</body>
</html>
