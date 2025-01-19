<?php
session_start();
require_once "../../../../../library/config.php";
checkUserSession($db);

$idTukang = $_GET['data'] ?? '';
if ($idTukang) {
    $idTukang = decryptUrl($idTukang);
    $data = query("SELECT * FROM tukang WHERE idTukang  = ?", [$idTukang])[0];
    $flagTukang = 'update';
} else {
    $flagTukang = 'add';
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
                        <form id="formTukangInput">
                            <div class="form-row">
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="tukangname">Nama</label>
                                    <input type="hidden" name="flagTukang" id="flagTukang" value="<?= $flagTukang ?>">
                                    <input type="hidden" name="idTukang" id="idTukang" value="<?= $idTukang ?>">
                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= $data['nama'] ?? '' ?>" autocomplete="off" placeholder="Nama">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="">No Telp</label>
                                    <input type="text" name="telp" id="telp" class="form-control" value="<?= $data['telp'] ?? '' ?>" autocomplete="off" placeholder="No Telp">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="alamat">Alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $data['alamat'] ?? '' ?>" autocomplete="off" placeholder="Alamat">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="idProyek">Proyek</label>
                                    <select class="form-control" id="idProyek" name="idProyek">
                                        <option value="">Pilih Proyek</option>
                                        <?php
                                        $proyekList = query("SELECT * FROM proyek");
                                        foreach ($proyekList as $proyek) {
                                            $selected = ($data['idProyek'] ?? '') == $proyek['idProyek'] ? 'selected' : '';
                                        ?>
                                            <option value="<?= $proyek['idProyek'] ?>" <?= $selected ?>><?= $proyek['namaProyek'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="bidang">Bidang</label>
                                    <select class="form-control" id="bidang" name="bidang">
                                        <option value="">Pilih Bidang</option>
                                        <option value="tukang" <?= ($data['bidang'] ?? '') == 'tukang' ? 'selected' : '' ?>>Tukang</option>
                                        <option value="laden" <?= ($data['bidang'] ?? '') == 'laden' ? 'selected' : '' ?>>Laden</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="idBidang">Jenis</label>
                                    <select class="form-control" id="jenis" name="jenis">
                                        <option value="">Pilih Jenis</option>
                                        <option value="harian" <?= ($data['jenis'] ?? '') == 'harian' ? 'selected' : '' ?>>Harian</option>
                                        <option value="borongan" <?= ($data['jenis'] ?? '') == 'borongan' ? 'selected' : '' ?>>Borongan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="idBidang">Gaji</label>
                                    <input type="number" name="gaji" id="gaji" class="form-control" value="<?= $data['gaji'] ?? '' ?>" autocomplete="off" placeholder="Gaji" onkeydown="return event.keyCode !== 38 && event.keyCode !== 40">
                                </div>
                            </div>
                        </form>

                        <button type="button" class="btn btn-<?= $flagTukang === 'add' ? 'update' : 'info' ?> btn-primary m-1 mt-3" onclick="prosesTukang()"><i class="ri-save-3-line"></i>Simpan</button>
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
    <script src="<?= BASE_URL_HTML ?>/system/data/tukang/tukang.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>