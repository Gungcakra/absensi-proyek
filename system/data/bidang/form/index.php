<?php
session_start();
require_once "../../../../library/config.php";
checkUserSession($db);

$idBidang = $_GET['data'] ?? '';
if ($idBidang) {
    $data = query("SELECT * FROM bidang WHERE idBidang  = ?", [$idBidang])[0];
    $flagBidang = 'update';
} else {
    $flagBidang = 'add';
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
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 bg-white p-2">
                        <form id="formBidangInput">
                            <div class="form-row">
                                <input type="hidden" name="flagBidang" id="flagBidang" value="<?= $flagBidang ?>">
                                <input type="hidden" name="idBidang" id="idBidang" value="<?= $idBidang ?>">

                                <div class="col-md-6 d-flex flex-column">
                                    <label for="tipe">Tipe</label>
                                    <select class="form-control" id="tipe" name="tipe">
                                        <option value="">Pilih Tipe</option>
                                        <option value="borongan" <?= ($data['tipe'] ?? '') == 'borongan' ? 'selected' : '' ?>>Borongan</option>
                                        <option value="harian" <?= ($data['tipe'] ?? '') == 'harian' ? 'selected' : '' ?>>Harian</option>
                                        <option value="bulanan" <?= ($data['tipe'] ?? '') == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="jenis">Jenis</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="">Pilih Jenis</option>
                                        <option value="tukang" <?= ($data['jenis'] ?? '') == 'tukang' ? 'selected' : '' ?>>Tukang</option>
                                        <option value="laden" <?= ($data['jenis'] ?? '') == 'laden' ? 'selected' : '' ?>>Laden</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="gaji">Gaji</label>
                                    <input type="text" class="form-control" id="gaji" name="gaji" value="<?= $data['gaji'] ?? '' ?>" autocomplete="off" placeholder="Gaji">
                                </div>
                            </div>
                        </form>

                        <button type="button" class="btn btn-<?= $flagBidang === 'add' ? 'update' : 'info' ?> btn-primary m-1 mt-3" onclick="prosesBidang()"><i class="ri-save-3-line"></i>Simpan</button>
                    </div>
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
    <script src="<?= BASE_URL_HTML ?>/system/data/bidang/bidang.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>