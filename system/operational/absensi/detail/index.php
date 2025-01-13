<?php
session_start();
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";

checkUserSession($db);
$idProyek = $_GET['data'] ?? '';
$idAbsensi = $_GET['absen'] ?? '';
if ($idProyek) {
    $idProyek = decryptUrl($idProyek);
}
if ($idAbsensi) {
    $idAbsensi = decryptUrl($idAbsensi);
    $tanggalAbsensi = query("SELECT * FROM absensi WHERE idAbsensi = ?", [$idAbsensi])[0]['tanggal'];
    $idProyek = query("SELECT * FROM absensi WHERE idAbsensi = ?", [$idAbsensi])[0]['idProyek'];
    $dataDetailAbsensi = query(
        "SELECT 
    proyek.*,
    absensi.idAbsensi,
    tukang.idTukang,
    tukang.nama AS namaTukang,
    tukang.bidang,
    tukang.jenis,
    absensi.waktuMasuk,
    absensi.waktuKeluar,
    IF(absensi.idTukang IS NOT NULL, 'Hadir', 'Tidak Hadir') AS status
FROM tukang
LEFT JOIN proyek ON proyek.idProyek = tukang.idProyek
LEFT JOIN absensi ON tukang.idTukang = absensi.idTukang 
    AND absensi.idProyek = tukang.idProyek 
    AND absensi.tanggal = ?
WHERE tukang.idProyek = ?",
        [$tanggalAbsensi, $idProyek]
    );
} else {
    $dataDetailAbsensi = query(
        "SELECT 
            proyek.*,
            absensi.idAbsensi,
            tukang.idTukang,
            tukang.nama AS namaTukang,
            tukang.bidang,
            tukang.jenis,
            absensi.waktuMasuk,
            absensi.waktuKeluar,
            IF(absensi.idTukang IS NOT NULL, 'Hadir', 'Tidak Hadir') AS status
        FROM tukang
        LEFT JOIN proyek ON proyek.idProyek = tukang.idProyek
        LEFT JOIN absensi ON tukang.idTukang = absensi.idTukang 
            AND absensi.idProyek = tukang.idProyek 
            AND absensi.tanggal >= CURDATE()
        WHERE tukang.idProyek = ?",
        [$idProyek]
    );
}

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
                <p class="font-size-20 font-weight-bold">Absensi Proyek </p>
                <div class="row">
                    <table class="table table-striped dataTable mt-4" role="grid"
                        aria-describedby="tukang-list-page-info">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>Tukang</th>
                                <th>Status</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataDetailAbsensi as $key => $row) { ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $row['namaTukang'] ?></td>
                                    <td>
                                        <div class="custom-control custom-switch custom-switch-text custom-switch-color custom-control-inline">
                                            <div class="custom-switch-inner">
                                                <input type="checkbox" class="custom-control-input bg-success" id="customSwitch-<?= $key ?>" <?= $row['status'] === 'Hadir' ? 'checked' : '' ?> onclick="prosesAbsensi(<?= htmlspecialchars(json_encode($row)) ?>)">
                                                <label class="custom-control-label" for="customSwitch-<?= $key ?>">
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <input type="checkbox" name="setHari" id="" setHari" onclick="setHari(<?= htmlspecialchars(json_encode($row)) ?>)" style="width: 20px; height: 20px;" <?= $row['setHari'] === 1 ? 'checked' : '' ?>>
                                    </td> -->

                                    <td>
                                        <input type="time" name="waktuMasuk" id="waktuMasuk" value="<?= timeStampToHourMinute($row['waktuMasuk']) ?? '' ?>" onchange="updateWaktuMasuk(<?= htmlspecialchars(json_encode($row)) ?>, this.value)">
                                    </td>
                                    <td>
                                        <input type="time" name="waktuKeluar" id="waktuKeluar" value="<?= timeStampToHourMinute($row['waktuKeluar'] ) ?? '' ?>" onchange="updateWaktuKeluar(<?= htmlspecialchars(json_encode($row)) ?>, this.value)">
                                    </td>
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