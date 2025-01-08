<?php
session_start();
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";

checkUserSession($db);

$idDetailAbsensi = $_GET['data'] ?? '';
if ($idDetailAbsensi) {
    $idDetailAbsensi = decryptUrl($idDetailAbsensi);
}
$idAbsensi = query("SELECT idAbsensi FROM detail_absensi WHERE idDetailAbsensi = ?", [$idDetailAbsensi])[0]['idAbsensi'];
$dataDetailAbsensi = query("SELECT 
                                    detail_absensi.*,
                                    detail_absensi.tanggal AS tanggalAbsensi,
                                    absensi.*,
                                    proyek.*,
                                    tukang.nama AS namaTukang,
                                    bidang.*
                                    FROM detail_absensi
                                    INNER JOIN absensi ON detail_absensi.idAbsensi = absensi.idAbsensi
                                    INNER JOIN proyek ON absensi.idProyek = proyek.idProyek
                                    INNER JOIN tukang ON detail_absensi.idTukang = tukang.idTukang
                                    INNER JOIN bidang ON tukang.idBidang = bidang.idBidang
                                    WHERE detail_absensi.idAbsensi = ?", [$idAbsensi]);
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
</head>

<body class=" color-light ">
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
            <div class="container-fluid bg-white p-4 rouned-md">
                <h4>Detail Absensi <?= $dataDetailAbsensi[0]['namaProyek'] ?> - <?= timeStampToTanggalNamaBulan($dataDetailAbsensi[0]['tanggalAbsensi']) ?></h4>
                <div class="row">
                    <table class="table table-striped dataTable mt-4" role="grid"
                    aria-describedby="tukang-list-page-info">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Tukang</th>
                                <th>Bidang</th>
                                <th>Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataDetailAbsensi as $key => $row){ ?>
                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= $row['namaTukang']?></td>
                                <td><?= $row['jenis']?></td>
                                <td><?= $row['tipe']?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Wrapper End-->

    <!-- Modal list start -->

    <!-- Footer  -->
    <?php require_once "{$constant('BASE_URL_PHP')}/system/footer.php" ?>

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
    <script src="<?= BASE_URL_HTML ?>/system/operational/absensi/absensi.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>