<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todo</title>
    <link rel="shortcut icon" href="<?= base_url("assets/images/dnc_logo.jpg"); ?>">

    <!-- Bootstrap CSS -->
    <link href="<?= base_url(); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/font-awesome/css/font-awesome.css" />

    <!-- Css -->
    <link href="<?= base_url(); ?>assets/css/variables.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/main.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/table.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/input.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/css/schedule.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="<?= base_url(); ?>assets/select2/select2.min.css" rel="stylesheet">

    <!-- JQuery -->
    <script src="<?= base_url(); ?>assets/jquery/jquery.min.js"></script>
    <link href="<?= base_url(); ?>assets/jquery/jquery-ui.css" rel="stylesheet" />
    <script src="<?= base_url(); ?>assets/jquery/jquery-ui.js"></script>
    
    <!-- Boostrap -->
    <script src="<?= base_url(); ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="<?= base_url(); ?>assets/js/input.js"></script>

    <script src="<?= base_url(); ?>assets/socket.io/socket.io.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/dataTable/dataTables.dataTables.css" />
    <script src="<?= base_url(); ?>assets/dataTable/dataTables.js"></script>

    <!-- DateRangePicker -->
    <script src="<?= base_url(); ?>assets/adminlte/plugins/moment/moment.min.js"></script>

    <!-- DateRangePicker -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/adminlte/plugins/daterangepicker/daterangepicker.css" />
    <script src="<?= base_url(); ?>assets/adminlte/plugins/daterangepicker/daterangepicker.js"></script>


    <!-- Toastr -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/toastr/toastr.min.css">
    <script src="<?= base_url(); ?>assets/toastr/toastr.min.js"></script>

    <!-- Full Calendar -->
    <script src='<?= base_url(); ?>assets/full-calendar/index.global.min.js'></script>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/tabler_icons/tabler-icons.min.css">

    <!-- Popper -->
    <!-- <script src='<?= base_url(); ?>assets/popper/popper.min.js'></script> -->

    <!-- TinyMCE -->
    <!-- <script src='<?= base_url(); ?>assets/tinymce/tinymce.min.js'></script> -->

    <!-- Frappe Gantt -->
    <!-- <link rel="stylesheet" href="<?= base_url(); ?>assets/frappe-gantt/frappe-gantt.min.css">
    <script src='<?= base_url(); ?>assets/frappe-gantt/frappe-gantt.min.js'></script> -->

    <!-- Dhtmlx Gantt -->
    <script src="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.8/codebase/dhtmlxgantt.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/dhtmlx-gantt@8.0.8/codebase/dhtmlxgantt.min.css" rel="stylesheet">

    <script src="<?= base_url('assets/chart_gpl/codebase/chart.min.js') ?>"></script>
    <link href="<?= base_url('assets/chart_gpl/codebase/chart.min.css') ?>" rel="stylesheet">

    <script type="text/javascript">
        var baseUrl = "<?php echo base_url(); ?>";
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <style>
        *,
        html,
        body {
            padding: 0px;
            margin: 0px;

            font-family: 'Roboto';
        }

        /* width */
        ::-webkit-scrollbar {
            width: 10px;
            height: 13px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #ddd;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .form-control {
            border-radius: 0 !important;
        }
    </style>
</head>

<body>
    <div class="container-fuild">