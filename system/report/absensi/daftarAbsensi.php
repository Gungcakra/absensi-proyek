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
$rentangTanggal = [
    range(1, 10),
    range(11, 20),
    range(21, $jumlahHariBulan)
];

// Ambil absensi berdasarkan idProyek dan rentang tanggal
$absensiBulan = query(
    "SELECT absensi.tanggal, absensi.idTukang 
     FROM absensi 
     WHERE absensi.idProyek = ? ",
    [$idProyek]
);

$absensiMap = [];
foreach ($absensiBulan as $absen) {
    $absensiMap[$absen['tanggal']][$absen['idTukang']] = true;
}


// Ambil data tukang berdasarkan proyek
$tukangList = query("SELECT * FROM tukang WHERE idProyek = ?", [$idProyek]);

?>

<h4 class="mt-2"><?= $namaProyek ?> - <?= namaBulan(intval($bulan)) ?> <?= $tahun ?></h4>

<?php
if ($rentangTanggal) {
    foreach ($rentangTanggal as $range) { ?>
        <table border="1" cellspacing="0" cellpadding="5" style="margin-top: 20px; width: 100%;">
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: center;">No</th>
                    <th rowspan="2" style="text-align: center;">Nama</th>
                    <th colspan="<?= count($range) ?>" style="text-align: center;">Tanggal <?= namaBulan($bulan) ?></th>
                    <th rowspan="2" style="text-align: center;">Jml</th>
                    <th rowspan="2" style="text-align: center;">GjH(Rp)</th>
                    <th rowspan="2" style="text-align: center;">Total</th>
                    <th rowspan="2" style="text-align: center;">Bon(Rp)</th>
                    <th rowspan="2" style="text-align: center;">Sisa(Rp)</th>
                    <th rowspan="2" style="text-align: center; width: 120px;">DTR(Rp)</th>
                    <th rowspan="2" style="text-align: center; width: 120px;">Tanda Tangan</th>
                    <th rowspan="2" style="text-align: center; width: 170px;">Keterangan</th>
                </tr>
                <tr>
                    <?php foreach ($range as $day) { ?>
                        <th style="text-align:center;"><?= $day ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grandTotalGaji = 0;
                $grandTotalBon = 0;
                $grandTotalSisa = 0;
                foreach ($tukangList as $key => $tukang) { ?>
                    <tr>
                        <td><?= $key+1 ?></td>
                        <td><?= $tukang['nama'] ?></td>
                        <?php
                        $totalBon = 0;
                        $hadirCount = 0.0;
                        foreach ($range as $day) {
                            $tanggal = "$tahun-$bulan-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $status = ' - ';
                            $hadirIncrement = 0.0;
                            if (isset($absensiMap[$tanggal][$tukang['idTukang']])) {
                                $timeQuery = query(
                                    "SELECT waktuMasuk, waktuKeluar FROM absensi 
                                     WHERE idTukang = ? AND DATE(tanggal) = ?",
                                    [$tukang['idTukang'], $tanggal]
                                );

                                if (!empty($timeQuery)) {
                                    $waktuMasuk = strtotime($timeQuery[0]['waktuMasuk'] ?? '');
                                    $waktuKeluar = strtotime($timeQuery[0]['waktuKeluar'] ?? '');

                                    if ($waktuMasuk && $waktuKeluar) {
                                        $durasiKerja = ($waktuKeluar - $waktuMasuk) / 3600; 
                                        if ($durasiKerja < 7) {
                                            $hadirIncrement = 0.5;
                                            $status = '0.5';
                                        } else {
                                            $hadirIncrement = 1.0;
                                            $status = '1';
                                        }
                                    } else {
                                        $hadirIncrement = 0.0;
                                        $status = '-';
                                    }
                                } else {
                                    $hadirIncrement = 0.0;
                                    $status = '-';
                                }
                            }
                            $hadirCount += $hadirIncrement;
                            echo "<td style=\"text-align:center;\">$status</td>";
                            $bonQuery = query(
                                "SELECT SUM(nominal) AS totalBon 
                                        FROM cashbon 
                                        WHERE idTukang = ? AND DATE(tanggal) = ?",
                                [$tukang['idTukang'], $tanggal]
                            );
                            $totalBon += $bonQuery[0]['totalBon'] ?? 0;
                        }

                        $gajiHarian = $tukang['gaji'] ?? 0;
                        $totalGaji = $gajiHarian * $hadirCount;
                        $sisa = $totalGaji - $totalBon;

                        $grandTotalGaji += $totalGaji;
                        $grandTotalBon += $totalBon;
                        $grandTotalSisa += $sisa;
                        ?>
                        <td style="text-align:center;"><?= $hadirCount ?></td>
                        <td style="text-align:center;"><?= rupiahTanpaRp($gajiHarian) ?></td>
                        <td style="text-align:center;"><?= rupiahTanpaRp($totalGaji) ?></td>
                        <td style="text-align:center;"><?= rupiahTanpaRp($totalBon) ?></td>
                        <td style="text-align:center;"><?= rupiahTanpaRp($sisa) ?></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                        <td style="text-align:center;"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="text-align:center; font-weight:bold;" colspan="<?= count($range) + 4 ?>">Total</td>
                    <td style="text-align:center; font-weight:bold;"><?= rupiahTanpaRp($grandTotalGaji) ?></td>
                    <td style="text-align:center; font-weight:bold;"><?= rupiahTanpaRp($grandTotalBon) ?></td>
                    <td style="text-align:center; font-weight:bold;"><?= rupiahTanpaRp($grandTotalSisa) ?></td>
                    <td style="text-align:center; font-weight:bold;"></td>
                    <td style="text-align:center; font-weight:bold;"></td>
                </tr>
            </tbody>
        </table>
    <?php }
} else { ?>
    <div class="alert alert-warning mt-2" role="alert">
        <div class="iq-alert-icon">
            <i class="ri-alert-line"></i>
        </div>
        <div class="iq-alert-text">Data Not Found!</div>
    </div>
<?php } ?>