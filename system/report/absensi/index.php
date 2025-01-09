<?php
session_start();
require_once "../../../library/config.php";

//CEK USER
checkUserSession($db);



?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= PAGE_TITLE ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= BASE_URL_HTML ?>/assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/css/backend-plugin.min.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/css/backend.css?v=1.0.0">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/remixicon/fonts/remixicon.css">

    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-calendar/dist/tui-calendar.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-date-picker/dist/tui-date-picker.css">
    <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-time-picker/dist/tui-time-picker.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <!-- Date Range -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>

<body class="  ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">

        <!-- Sidebar  -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/sidebar.php" ?>

        <!-- NAVBAR  -->
        <?php require_once "{$constant('BASE_URL_PHP')}/system/navbar.php" ?>

        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div class="header-title">
                                    <h4 class="card-title">Daftar Absensi</h4>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="row justify-content-between">
                                    <div class="col-sm-6 col-md-6">
                                        <div id="user_list_datatable_info" class="dataTables_filter">

                                            <form class="mr-3 position-relative d-flex ">
                                                <!-- <div class="col-md-6 m-0 p-0">
                                                        <input type="text" class="form-control" id="searchQuery" placeholder="Search"
                                                            name="searchQuery"
                                                            autocomplete="off"
                                                            onkeyup="cariDaftarAbsensi()"
                                                            aria-controls="user-list-table">
                                                    </div> -->
                                                <!-- <div class="col-md-2 m-0 p-0 ml-2">
                                                            <select class="custom-select" id="limit" name="limit" onclick="cariDaftarAbsensi()">
                                                                <option value="10">10</option>
                                                                <option value="20">20</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select>
                                                        
                                                    </div> -->
                                                <div class="input-group col">
                                                    <input type="month" class="form-control bg-white" name="bulanTahun" id="bulanTahun" onchange    ="cariDaftarAbsensi()">
                                                </div>
                                                <div class="col p-0 m-0">
                                                    <select class="custom-select" id="idProyek" name="idProyek" onchange="cariDaftarAbsensi()">
                                                        <option value="">Pilih Proyek</option>
                                                        <?php
                                                        $proyek = query("SELECT * FROM proyek", []);

                                                        foreach ($proyek as $key => $row) {


                                                        ?>
                                                            <option value="<?= $row['idProyek'] ?>"><?= $row['namaProyek'] ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="user-list-files d-flex justify-content-end">
                                            <!-- <button class="btn btn-primary mr-1" href="javascript:void();">
                                                    Print
                                                </button>
                                                <button class="btn btn-primary mr-1" href="javascript:void();">
                                                    Excel
                                                </button> -->
                                            <a class="btn btn-success mr-1 text-white" onclick="generateLaporan()"> <i class="las la-file-pdf"></i> Laporan</a>

                                        </div>
                                    </div>
                                </div>
                                <div id="daftarAbsensi" class="w-100">

                                </div>
                                <!-- <div class="row justify-content-between mt-3" id="pagination">

                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper End-->

    <!-- Modal list start -->


    <?php require_once "{$constant('BASE_URL_PHP')}/system/footer.php" ?>

    <!-- JQUERY -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Backend Bundle JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/backend-bundle.min.js"></script>


    <!-- Table Treeview JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/table-treeview.js"></script>

    <!-- Chart Custom JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/customizer.js"></script>

    <!-- Chart Custom JavaScript -->
    <script async src="<?= BASE_URL_HTML ?>/assets/js/chart-custom.js"></script>
    <!-- Chart Custom JavaScript -->
    <script async src="<?= BASE_URL_HTML ?>/assets/js/slider.js"></script>

    <!-- app JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/app.js"></script>

    <script src="<?= BASE_URL_HTML ?>/assets/vendor/moment.min.js"></script>
    <!-- MAIN JS -->

    <script src="<?= BASE_URL_HTML ?>/system/report/absensi/absensi.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Date Range -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</body>

</html>