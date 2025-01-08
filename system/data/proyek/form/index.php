<?php
session_start();
require_once "../../../../library/config.php";
checkUserSession($db);

$idProyek = $_GET['data'] ?? '';
if ($idProyek) {
    $data = query("SELECT * FROM proyek WHERE idProyek  = ?", [$idProyek])[0];
    $flagProyek = 'update';
} else {
    $flagProyek = 'add';
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
                        <form id="formProyekInput">
                            <div class="form-row">
                                <input type="hidden" name="flagProyek" id="flagProyek" value="<?= $flagProyek ?>">
                                <input type="hidden" name="idProyek" id="idProyek" value="<?= $idProyek ?>">

                                <div class="col-md-6 d-flex flex-column">
                                    <label for="namaProyek">Nama Proyek</label>
                                    <input type="text" class="form-control" id="namaProyek" name="namaProyek" value="<?= $data['namaProyek'] ?? '' ?>" autocomplete="off" placeholder="Nama Proyek">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="namaPemilik">Nama Pemilik</label>
                                    <input type="text" class="form-control" id="namaPemilik" name="namaPemilik" value="<?= $data['namaPemilik'] ?? '' ?>" autocomplete="off" placeholder="Nama Pemilik">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="tanggalMulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai" value="<?= $data['tanggalMulai'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="tanggalTarget">Tanggal Target</label>
                                    <input type="date" class="form-control" id="tanggalTarget" name="tanggalTarget" value="<?= $data['tanggalTarget'] ?? '' ?>">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="status">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" class="form-control" value="<?= $data['alamat'] ?? '' ?>" placeholder="Alamat Proyek" autocomplete="off">
                                </div>
                                <div class="col-md-6 d-flex flex-column">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">Pilih Status</option>
                                        <option value="Proses" <?= ($data['status'] ?? '') == 'proses' ? 'selected' : '' ?>>Proses</option>
                                        <option value="Selesai" <?= ($data['status'] ?? '') == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </form>

                        <button type="button" class="btn btn-<?= $flagProyek === 'add' ? 'update' : 'info' ?> btn-primary m-1 mt-3" onclick="prosesProyek()"><i class="ri-save-3-line"></i>Simpan</button>
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
    <script src="<?= BASE_URL_HTML ?>/system/data/proyek/proyek.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>