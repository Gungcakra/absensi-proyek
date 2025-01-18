<?php
session_start();
require_once "../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";

checkUserSession($db);

// Ambil idProyek dan bulanTahunAbsensi dari POST
$idProyek = $_POST['idProyek'] ?? '';
$bulanTahunAbsensi = $_POST['bulanTahun'] ?? '';

if (empty($idProyek) || empty($bulanTahunAbsensi)) {
    die('<div class="alert alert-warning mt-2" role="alert">
        <div class="iq-alert-icon">
            <i class="ri-alert-line"></i>
        </div>
        <div class="iq-alert-text">Pilih Periode dan Proyek Terlebih Dahulu!</div>
    </div>');
}

// Pisahkan bulan dan tahun dari $bulanTahunAbsensi
list($tahun, $bulan) = explode('-', $bulanTahunAbsensi);

// Ambil data proyek
$namaProyekQuery = query("SELECT namaProyek FROM proyek WHERE idProyek = ?", [$idProyek]);
if (empty($namaProyekQuery)) {
    die("Error: Proyek dengan idProyek = {$idProyek} tidak ditemukan.");
}
$namaProyek = $namaProyekQuery[0]['namaProyek'];

// Rentang tanggal berdasarkan bulan dan tahun
$jumlahHariBulan = (int)date('t', strtotime("$tahun-$bulan-01"));
$rentangTanggal = range(1, $jumlahHariBulan);

// Ambil absensi berdasarkan idProyek
$absensiBulan = query(
    "SELECT absensi.tanggal, absensi.waktuMasuk, absensi.waktuKeluar, absensi.idTukang 
     FROM absensi 
     WHERE absensi.idProyek = ?",
    [$idProyek]
);

$absensiMap = [];
foreach ($absensiBulan as $absen) {
    $absensiMap[$absen['tanggal']][$absen['idTukang']] = [
        'waktuMasuk' => $absen['waktuMasuk'],
        'waktuKeluar' => $absen['waktuKeluar'],
    ];
}

// Ambil data tukang berdasarkan proyek
$tukangList = query("SELECT * FROM tukang WHERE idProyek = ?", [$idProyek]);
?>
<div class="row mt-2">
    <div class="col">
        <h5>Daftar Absensi</h5>
    </div>
    <div class="col">
        <h5>Periode <?= namaBulan(intval($bulan)) . ' ' . $tahun . ' Proyek ' . $namaProyek ?></h5>
    </div>
</div>
<div class="row">
    <?php
    foreach ($tukangList as $index => $tukang) {
        if ($index % 2 == 0 && $index != 0) {
            echo '</div><div class="row">';
        }
    ?>
        <div class="col-md-6">
            <h5 class="mt-2">Nama : <?= $tukang['nama'] ?></h5>
            <table border="1" cellspacing="0" cellpadding="5" style="margin-top: 20px; width: 100%">
                <thead>
                    <tr>
                        <th style="text-align: center" rowspan="2">Tanggal</th>
                        <th colspan="2" style="text-align: center">Jam Kerja</th>
                        <th style="text-align: center" rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                        <th style="text-align: center">Datang</th>
                        <th style="text-align: center">Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rentangTanggal as $day) {
                        $tanggal = "$tahun-$bulan-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $absensi = $absensiMap[$tanggal][$tukang['idTukang']] ?? null;

                        $waktuMasuk = ($absensi && !empty($absensi['waktuMasuk'])) ? getHourFromTimeStamp($absensi['waktuMasuk']) : ' - ';
                        $waktuKeluar = ($absensi && !empty($absensi['waktuKeluar'])) ? getHourFromTimeStamp($absensi['waktuKeluar']) : ' - ';
                    ?>
                        <tr>
                            <td style="text-align: center;"><?= getDateFromDate($tanggal)?></td>
                            <td style="text-align: center;"><?= $waktuMasuk?></td>
                            <td style="text-align: center;"><?= $waktuKeluar?></td>
                            <td style="text-align: center;"></td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    <?php
    }
    ?>
</div>