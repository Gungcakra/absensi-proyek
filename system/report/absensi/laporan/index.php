<?php
session_start();
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
require_once '../../../../vendor/autoload.php';

use Dompdf\Dompdf;

// Periksa sesi pengguna
checkUserSession($db);

// Ambil idProyek dan bulanTahunAbsensi dari POST
$idProyek = $_POST['idProyek'] ?? '';
$bulanTahunAbsensi = $_POST['bulanTahun'] ?? '';

if (empty($idProyek) || empty($bulanTahunAbsensi)) {
    die("Error: idProyek atau bulanTahunAbsensi tidak boleh kosong.");
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

// HTML untuk PDF
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Absensi Proyek</title>
    <style>
        body{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            page-break-after: always;
            height: auto;
        }

        table,
        th,
        td {
            border: 1px solid black;
            height: 20px
        }

        th,
        td {
            padding: 5px;
            text-align: center;
            height: 20px
        }
    </style>
</head>

<body>
    <h4 style="text-align: center;"><?= $namaProyek ?> - <?= namaBulan(intval($bulan)) ?> <?= $tahun?></h4>

    <?php foreach ($rentangTanggal as $range) { ?>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama</th>
                    <th colspan="<?= count($range) ?>">Tanggal <?= namaBulan($bulan) ?></th>
                    <th rowspan="2">Jml</th>
                    <th rowspan="2">GjH(Rp)</th>
                    <th rowspan="2">Total(Rp)</th>
                    <th rowspan="2">Bon(Rp)</th>
                    <th rowspan="2">Sisa(Rp)</th>
                    <th rowspan="2">DTR(Rp)</th>
                    <th rowspan="2" style="text-align: center; width: 120px;">Tanda Tangan</th>
                    <th rowspan="2" style="text-align: center; width: 170px;">Keterangan</th>
                </tr>
                <tr>
                    <?php foreach ($range as $day) { ?>
                        <th><?= $day ?></th>
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
                        $hadirCount = 0.0;
                        $totalBon = 0;
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
                                        $status = ' - ';
                                    }
                                } else {
                                    $hadirIncrement = 0.0;
                                    $status = ' - ';
                                }
                            }
                            $hadirCount += $hadirIncrement;
                            echo "<td>$status</td>";
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
                        <td><?= $hadirCount ?></td>
                        <td><?= rupiahTanpaRp($gajiHarian) ?></td>
                        <td><?= rupiahTanpaRp($totalGaji) ?></td>
                        <td><?= rupiahTanpaRp($totalBon) ?></td>
                        <td><?= rupiahTanpaRp($sisa) ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="font-weight:bold;" colspan="<?= count($range) + 4 ?>">Total</td>
                    <td style="font-weight:bold;"><?= rupiahTanpaRp($grandTotalGaji) ?></td>
                    <td style="font-weight:bold;"><?= rupiahTanpaRp($grandTotalBon) ?></td>
                    <td style="font-weight:bold;"><?= rupiahTanpaRp($grandTotalSisa) ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <br>
    <?php } ?>
</body>

</html>
<?php
$html = ob_get_clean();

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('Legal', 'landscape');
$dompdf->render();

// Kirim PDF ke browser untuk diunduh
$dompdf->stream("Laporan Absesi Tukang {$namaProyek} - " . namaBulan(intval($bulan)) . " {$tahun}" . ".pdf", ["Attachment" => true]);
?>