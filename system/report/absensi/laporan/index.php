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
        table {
            border-collapse: collapse;
            width: 100%;
            page-break-after: always;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h4 style="text-align: center;"><?= $namaProyek ?></h4>

    <?php foreach ($rentangTanggal as $range) { ?>
        <table>
            <thead>
                <tr>
                    <th rowspan="2">Nama</th>
                    <th colspan="<?= count($range) ?>">Tanggal <?= namaBulan($bulan) ?></th>
                    <th rowspan="2">Jml</th>
                    <th rowspan="2">Gaji Harian</th>
                    <th rowspan="2">Total</th>
                    <th rowspan="2">Bon</th>
                    <th rowspan="2">Sisa</th>
                </tr>
                <tr>
                    <?php foreach ($range as $day) { ?>
                        <th><?= $day ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tukangList as $tukang) { ?>
                    <tr>
                        <td><?= $tukang['nama'] ?></td>
                        <?php
                        $hadirCount = 0;
                        $totalBon = 0;
                        foreach ($range as $day) {
                            $tanggal = "$tahun-$bulan-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $status = isset($absensiMap[$tanggal][$tukang['idTukang']]) ? 'Hadir' : '-';
                            if ($status === 'Hadir') {
                                $hadirCount++;
                            }
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
                        ?>
                        <td><?= $hadirCount ?></td>
                        <td><?= rupiah($gajiHarian) ?></td>
                        <td><?= rupiah($totalGaji) ?></td>
                        <td><?= rupiah($totalBon) ?></td>
                        <td><?= rupiah($sisa) ?></td>
                    </tr>
                <?php } ?>
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
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

// Kirim PDF ke browser untuk diunduh
$dompdf->stream("Laporan Absesi Tukang.pdf", ["Attachment" => true]);
?>
