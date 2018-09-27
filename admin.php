<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="./css/autocomplete.css" type="text/css" media="screen">
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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src='./js/moment.min.js'></script>
    <script src='./js/jquery.qtip.min.js'></script>
    <script src="./js/chosen.jquery.js"></script>
    <script src="./js/bootstrap-datepicker.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="./js/sweetalert2.all.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
</head>
<body>
<div id="wrapper">
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
            </div>
            <div class="col-md-12">
                <h1 class="page-header"> <h1>Edite calendrier transforme to json</h1></h1>
                <table id="datatable" class="table table-bordered table-striped dataTable no-footer" style="width:100%">
                </table>
                <button id="saveBtn" type="button" class="btn btn-primary active">sauvegarder le json</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        table = $('#datatable').DataTable({
            "pagingType": "simple_numbers",
            "paging": false,
            "ajax": {
                "url": "./listFormations.json",
                "dataSrc": ""
            },
            columns: [
                {
                    "visible": true,
                    "width": '5%',
                    "targets": 0,
                    "data": "name",
                    "title":"name",
                    "className" : "center-text",
                    "render": function ( data, type, row, meta ) {
                        if (type === 'display') {
                            return "<input type='text'  class='form-control input-lg autoSizeInput editChangeName' placeholder='Name' value='"+data+"'>";
                        }
                        return data
                    }
                },
                {
                    "visible": true,
                    "width": '5%',
                    "targets": 1,
                    "data": "export_url",
                    "title": "export url",
                    "className" : "center-text",
                    "render": function ( data, type, row, meta ) {
                        if (type === 'display') {
                            return "<input type='text'  class='form-control input-lg autoSizeInput editExportUrl' placeholder='export_url' value='"+data+"'>";
                        }
                        return data
                    }
                },
                {
                    "visible": true,
                    "width": '5%',
                    "targets": 2,
                    "data": "optgroup",
                    "title": "optgroup",
                    "className" : "center-text",
                    "render": function ( data, type, row, meta ) {
                        if (type === 'display') {
                            return "<input type='text'  class='form-control input-lg autoSizeInput editOptgroup' placeholder='optgroup' value='"+data+"'>";
                        }
                        return data
                    }
                },
                {
                    "visible": true,
                    "width": '5%',
                    "targets": 3,
                    "data": "optdescription",
                    "title": "optdescription",
                    "className" : "center-text",
                    "render": function ( data, type, row, meta ) {
                        if (type === 'display') {
                            return "<input type='text'  class='form-control input-lg autoSizeInput editOptdescription' placeholder='optdescription' value='"+data+"'>";
                        }
                        return data
                    }
                },
                {
                    "visible": true,
                    "width": '5%',
                    "targets": 3,
                    "data": "action",
                    "title": "action",
                    "className" : "center-text",
                    "render": function ( data, type, row, meta ) {
                        var btnHTML = '<div class="btn-group">' +
                            '<a class="btn btn-success btn-xs no-margins addRow">' +
                            '<i class="fa fa-plus"></i></a>' +
                            '<a class="btn btn-danger btn-xs no-margins deleteRow">' +
                            '<i class="fa fa-trash"></i></a>' +
                            '</div>';
                        return btnHTML;
                    }
                }
            ]
        });

        $('#datatable tbody').on('click','.addRow', function (evt, params) {
            var rowNode = table.row.add(  {"name":"","export_url":"","optgroup":"","optdescription":""} ).draw().node();
            $( rowNode ).css( 'color', 'red' ).animate( { color: 'black' } );
        });

        $('#datatable tbody').on('click','.deleteRow', function (evt, params) {
            table.row($(this).parents('tr')).remove().draw( false );
        });

        $('#saveBtn').on('click', function (evt, params) {
            var aFormationNew = table.data().toArray();
            var recursiveEncoded = encodeURI(JSON.stringify(aFormationNew) );

            $.ajax({
                url: "ajaxUpdateListFormation.php",
                data: {data_json: recursiveEncoded},
                type: "POST",
                dataType: 'html',
                success: function (e) {
                    toastr.success("save ok","Info :")
                },
                error: function (e) {
                    toastr.error("save not ok","error :")
                }
            });
        });

        $('#datatable tbody').on('change','.editChangeName', function (evt, params) {
            let oData = table.row($(this).parents("tr")).data();
            oData['name'] = $(this).val();
            table.row($(this).parents("tr")).data(oData).draw();
        });

        $('#datatable tbody').on('change','.editExportUrl', function (evt, params) {
            let oData = table.row($(this).parents("tr")).data();
            oData['export_url'] = $(this).val();
            table.row($(this).parents("tr")).data(oData).draw();
        });

        $('#datatable tbody').on('change','.editOptgroup', function (evt, params) {
            let oData = table.row($(this).parents("tr")).data();
            oData['optgroup'] = $(this).val();
            table.row($(this).parents("tr")).data(oData).draw();
        });

        $('#datatable tbody').on('change','.editOptdescription', function (evt, params) {
            let oData = table.row($(this).parents("tr")).data();
            oData['optdescription'] = $(this).val();
            table.row($(this).parents("tr")).data(oData).draw();
        });
    });
</script>
</body>
</html>