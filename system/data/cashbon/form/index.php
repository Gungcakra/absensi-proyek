<?php
session_start();
require_once "../../../../library/config.php";
checkUserSession($db);

$idCashbon = $_GET['data'] ?? '';
if ($idCashbon) {
    $data = query("SELECT * FROM cashbon WHERE idCashbon  = ?", [$idCashbon])[0];
    $flagCashbon = 'update';
} else {
    $flagCashbon = 'add';
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
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <form id="formCashbonInput">
                            <div class="form-row">
                                <div class="col-md-6 d-flex flex-column">
                                    <input type="hidden" name="flagCashbon" id="flagCashbon" value="<?= $flagCashbon ?>">
                                    <input type="hidden" name="idCashbon" id="idCashbon" value="<?= $idCashbon ?>">
                                    <label for="idProyek">Tukang</label>
                                    <select class="form-control select-tukang" id="idTukang" name="idTukang">
                                        <option value="">Pilih Tukang</option>
                                        <?php
                                        $tukangList = query("SELECT * FROM tukang");
                                        foreach ($tukangList as $tukang) {
                                            $selected = ($data['idTukang'] ?? '') == $tukang['idTukang'] ? 'selected' : '';
                                        ?>
                                            <option value="<?= $tukang['idProyek'] ?>" <?= $selected ?>><?= $tukang['nama'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="nominal">Nominal</label>
                                    <input type="number" name="nominal" id="nominal" class="form-control" value="<?= $data['nominal'] ?? '' ?>" autocomplete="off" placeholder="Nominal" onkeydown="return event.keyCode !== 38 && event.keyCode !== 40">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="keterangan">Keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?= $data['keterangan'] ?? '' ?>" autocomplete="off" placeholder="Keterangan">
                                </div>
                          
                               
                            </div>
                        </form>

                        <button type="button" class="btn btn-<?= $flagCashbon === 'add' ? 'update' : 'info' ?> btn-primary m-1 mt-3" onclick="prosesCashbon()"><i class="ri-save-3-line"></i>Simpan</button>
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
    <script src="<?= BASE_URL_HTML ?>/system/data/cashbon/cashbon.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>