<?php
/**
 * Created by PhpStorm.
 * User: remid
 * Date: 09/10/2017
 * Time: 11:57
 */

require_once './BuilderCalendar.php';
require_once './BuilderFormation.php';

$oBuilderCalendar  = new BuilderCalendar("./listFormations.json");
$jEvents           = $oBuilderCalendar->createCalendar();
$oBuilderFormation = $oBuilderCalendar->oBuilderFormation;

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
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

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
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>

</head>
<body>

<div id="top">
    <div class="left">
        <div>
            <form id="formFormation">
                <select id="selectFormation" data-placeholder="choisir votre formation & groupe" class="chosen-select">
                    <?= $oBuilderFormation->toHTMLOption() ?>
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
    </div>
    <div class="clear"></div>
</div>

<div class="container-fluid" style="margin-top: 20px;">
    <div class="row">
        <div id='calendar' class='col-lg-10 col-lg-offset-1 col-sm-12'></div>
    </div>
</div>


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

            $.ajax({
                url: "formationAjax.php",
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

        $.ajax({
            url: "fileFormation.php",
            data: {formation: $('#selectFormation option:selected').val()},
            type: "POST",
            dataType: 'html',
            success: function (e) {
                console.log(e);

                toastr.success(e,"Info :")
            },
            error: function (e) {
                console.log(JSON.stringify(e));
            }
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
