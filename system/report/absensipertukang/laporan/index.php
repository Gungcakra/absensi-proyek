<?php
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
require_once "{$constant('BASE_URL_PHP')}/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
checkUserSession($db);

// Ambil idTukang dan bulanTahunAbsensi dari POST
$idTukang = $_POST['idTukang'] ?? '';
$bulanTahunAbsensi = $_POST['bulanTahun'] ?? '';

if (empty($idTukang) || empty($bulanTahunAbsensi)) {
    die('<div class="alert alert-warning mt-2" role="alert">
        <div class="iq-alert-icon">
            <i class="ri-alert-line"></i>
        </div>  
        <div class="iq-alert-text">Pilih Periode dan Tukang Terlebih Dahulu!</div>
    </div>');
}

// Pisahkan bulan dan tahun dari $bulanTahunAbsensi
list($tahun, $bulan) = explode('-', $bulanTahunAbsensi);

// Rentang tanggal berdasarkan bulan dan tahun
$jumlahHariBulan = (int)date('t', strtotime("$tahun-$bulan-01"));
$rentangTanggal = [
    range(1, 10),
    range(11, 20),
    range(21, $jumlahHariBulan)
];

// Ambil absensi berdasarkan idTukang dan rentang tanggal
$absensiBulan = query(
    "SELECT absensi.tanggal, absensi.idTukang 
     FROM absensi 
     WHERE absensi.idTukang = ?",
    [$idTukang]
);

$absensiMap = [];
foreach ($absensiBulan as $absen) {
    $absensiMap[$absen['tanggal']] = true;
}

// Ambil data tukang
$tukangQuery = query("SELECT * FROM tukang WHERE idTukang = ?", [$idTukang]);
if (empty($tukangQuery)) {
    die("Error: Tukang dengan idTukang = {$idTukang} tidak ditemukan.");
}
$tukang = $tukangQuery[0];

$namaTukang = $tukang['nama'];

// Inisialisasi DOMPDF
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

ob_start(); // Start output buffering
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h4 class="mt-2">Absensi <?= htmlspecialchars($namaTukang) ?> - <?= namaBulan(intval($bulan)) ?> <?= htmlspecialchars($tahun) ?></h4>

    <?php if ($rentangTanggal): ?>
        <?php foreach ($rentangTanggal as $range): ?>
            <table>
                <thead>
                    <tr>
                        <th colspan="<?= count($range) ?>">Tanggal <?= namaBulan($bulan) ?></th>
                        <th rowspan="2">Jml</th>
                        <th rowspan="2">Gaji Harian</th>
                        <th rowspan="2">Total</th>
                        <th rowspan="2">Bon</th>
                        <th rowspan="2">Sisa</th>
                    </tr>
                    <tr>
                        <?php foreach ($range as $day): ?>
                            <th><?= htmlspecialchars($day) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        $totalBon = 0;
                        $hadirCount = 0.0;
                        foreach ($range as $day):
                            $tanggal = "$tahun-$bulan-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                            $status = ' - ';
                            $hadirIncrement = 0.0;

                            if (isset($absensiMap[$tanggal])) {
                                $timeQuery = query(
                                    "SELECT waktuMasuk, waktuKeluar FROM absensi 
                                     WHERE idTukang = ? AND DATE(tanggal) = ?",
                                    [$idTukang, $tanggal]
                                );

                                if (!empty($timeQuery)) {
                                    $waktuMasuk = strtotime($timeQuery[0]['waktuMasuk'] ?? '');
                                    $waktuKeluar = strtotime($timeQuery[0]['waktuKeluar'] ?? '');

                                    if ($waktuMasuk && $waktuKeluar) {
                                        $durasiKerja = ($waktuKeluar - $waktuMasuk) / 3600; 
                                        if ($durasiKerja < 7) {
                                            $hadirIncrement = 0.5;
                                            $status = 'Setengah Hari';
                                        } else {
                                            $hadirIncrement = 1.0;
                                            $status = 'Hadir';
                                        }
                                    }
                                }
                            }

                            $hadirCount += $hadirIncrement;
                        ?>
                            <td><?= htmlspecialchars($status) ?></td>
                        <?php
                            $bonQuery = query(
                                "SELECT SUM(nominal) AS totalBon 
                                 FROM cashbon 
                                 WHERE idTukang = ? AND DATE(tanggal) = ?",
                                [$idTukang, $tanggal]
                            );
                            $totalBon += $bonQuery[0]['totalBon'] ?? 0;
                        endforeach;

                        $gajiHarian = $tukang['gaji'] ?? 0;
                        $totalGaji = $gajiHarian * $hadirCount;
                        $sisa = $totalGaji - $totalBon;
                        ?>
                        <td><?= htmlspecialchars($hadirCount) ?></td>
                        <td><?= rupiah($gajiHarian) ?></td>
                        <td><?= rupiah($totalGaji) ?></td>
                        <td><?= rupiah($totalBon) ?></td>
                        <td><?= rupiah($sisa) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning mt-2" role="alert">
            <div class="iq-alert-icon">
                <i class="ri-alert-line"></i>
            </div>
            <div class="iq-alert-text">Data Not Found!</div>
        </div>
    <?php endif; ?>
</body>
</html>
<?php
$html = ob_get_clean(); // Get the buffered content

// Load HTML into Dompdf
$dompdf->loadHtml($html);

// Set paper size (optional)
$dompdf->setPaper('A4', 'portrait');

// Render PDF (first pass: convert HTML to PDF)
$dompdf->render();

// Output the generated PDF to browser
$dompdf->stream('absensi_' . $namaTukang . '_' . $tahun . '_' . $bulan . '.pdf', array('Attachment' => 1)); // 1 means download
?>
