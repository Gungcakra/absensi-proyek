<?php
session_start();
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
require_once "{$constant('BASE_URL_PHP')}/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

checkUserSession($db);

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

[$tahun, $bulan] = explode('-', $bulanTahunAbsensi);

$namaProyekQuery = query("SELECT namaProyek FROM proyek WHERE idProyek = ?", [$idProyek]);
if (empty($namaProyekQuery)) {
    die("Error: Proyek dengan idProyek = {$idProyek} tidak ditemukan.");
}
$namaProyek = $namaProyekQuery[0]['namaProyek'];

$jumlahHariBulan = (int)date('t', strtotime("$tahun-$bulan-01"));
$rentangTanggal = range(1, $jumlahHariBulan);

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

$tukangList = query("SELECT * FROM tukang WHERE idProyek = ?", [$idProyek]);

$html = '';
$html .= '<style>
    .page-break { page-break-before: always; }
    .title { text-align: center; font-size: 16px; font-weight: bold; }
    .subtitle { text-align: center; font-size: 14px; font-weight: bold; }
    .table-container { width: 50%; float: left; padding-right: 10px; }
    table { width: 100%; margin-top: 20px; border-collapse: collapse; }
    th, td { border: 1px solid black; padding: 5px; text-align: center; }
</style>';

foreach ($tukangList as $index => $tukang) {
    if ($index % 2 == 0) {
        if ($index != 0) {
            $html .= '<div class="page-break"></div>';
        }
        $html .= '<div class="title">Daftar Absensi</div>';
        $html .= '<div class="subtitle">Periode ' . namaBulan(intval($bulan)) . ' ' . $tahun . ' Proyek ' . $namaProyek . '</div><br/>';
    }

    $html .= '<div class="table-container">';
    $html .= '<h5>Nama: ' . $tukang['nama'] . '</h5>';
    $html .= '<table>';
    $html .= '<thead>
                <tr>
                    <th rowspan="2">Tanggal</th>
                    <th colspan="2">Jam Kerja</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Datang</th>
                    <th>Pulang</th>
                </tr>
            </thead>';
    $html .= '<tbody>';

    foreach ($rentangTanggal as $day) {
        $tanggal = "$tahun-$bulan-" . str_pad($day, 2, '0', STR_PAD_LEFT);
        $absensi = $absensiMap[$tanggal][$tukang['idTukang']] ?? null;

        $waktuMasuk = ($absensi && !empty($absensi['waktuMasuk'])) ? getHourFromTimeStamp($absensi['waktuMasuk']) : '';
        $waktuKeluar = ($absensi && !empty($absensi['waktuKeluar'])) ? getHourFromTimeStamp($absensi['waktuKeluar']) : '';

        $html .= '<tr>';
        $html .= '<td>' . getDateFromDate($tanggal) . '</td>';
        $html .= "<td>{$waktuMasuk}</td>";
        $html .= "<td>{$waktuKeluar}</td>";
        $html .= '<td></td>';
        $html .= '</tr>';
    }

    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';
}

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

$dompdf->setPaper('Legal', 'portrait');

$dompdf->render();

// To download the file
$dompdf->stream('Daftar Absensi ' . namaBulan(intval($bulan)) . ' ' . $tahun . '.pdf', ['Attachment' => 1]);
?>
