<?php
require_once '../../../../library/config.php';
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
$idProyek = $_POST['idProyek'] ?? '';
$idAbsensi = $_POST['idAbsensi'] ?? '';
$tanggalHariIni = $_POST['tanggalAbsensi'] ?? '';

$dataDetailAbsensi = query(
    "SELECT 
    proyek.*,
    tukang.idTukang,
    tukang.nama AS namaTukang,
    tukang.bidang,
    tukang.jenis,
    MAX(absensi.idAbsensi) AS idAbsensi,
    MAX(absensi.waktuMasuk) AS waktuMasuk,
    MAX(absensi.waktuKeluar) AS waktuKeluar,
    IF(MAX(absensi.idTukang) IS NOT NULL, 'Hadir', 'Tidak Hadir') AS status
FROM tukang
LEFT JOIN proyek ON proyek.idProyek = tukang.idProyek
LEFT JOIN absensi ON tukang.idTukang = absensi.idTukang 
                   AND tukang.idProyek = absensi.idProyek 
                   AND absensi.tanggal = ?
WHERE tukang.idProyek = ?
GROUP BY tukang.idTukang, tukang.nama, tukang.bidang, tukang.jenis, proyek.idProyek
ORDER BY tukang.nama ASC;
",
    [$tanggalHariIni, $idProyek]
);
?>

<head>
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
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
                    <input type="time" name="waktuMasuk" id="waktuMasuk-<?= $key ?>"
                        value="<?= !empty($row['waktuMasuk']) ? timeStampToHourMinute($row['waktuMasuk']) : '00:00' ?>"
                        onchange="updateWaktuMasuk(<?= htmlspecialchars(json_encode($row)) ?>, this.value)">

                </td>
                <td>
                    <input type="time" name="waktuKeluar" id="waktuKeluar-<?= $key ?>"
                        value="<?= !empty($row['waktuKeluar']) ? timeStampToHourMinute($row['waktuKeluar']) : '00:00' ?>"
                        onchange="updateWaktuKeluar(<?= htmlspecialchars(json_encode($row)) ?>, this.value)">
                </td>

            </tr>
        <?php } ?>
    </tbody>
</table>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>