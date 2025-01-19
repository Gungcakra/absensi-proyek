<?php
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
require_once "../../../../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

checkUserSession($db);

$idProyek = $_POST['idProyek'] ?? '';
$bulanTahunAbsensi = $_POST['bulanTahun'] ?? '';
$tipe = 0;
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
$rentangTanggal = [
    range(1, 10),
    range(11, 20),
    range(21, $jumlahHariBulan)
];

$tukangList = query("SELECT * FROM tukang WHERE idProyek = ? AND tipe = ?", [$idProyek, $tipe]);

$html = "<h4 style='text-align:center;'>{$namaProyek} - " . namaBulan(intval($bulan)) . " {$tahun}</h4>";

foreach ($rentangTanggal as $range) {
    $html .= "<table border='1' cellspacing='0' cellpadding='5' style='margin-top: 20px; width: 100%;'>";
    $html .= "<thead>
                <tr>
                    <th rowspan='2' style='text-align: center;'>No</th>
                    <th rowspan='2' style='text-align: center;'>Nama</th>
                    <th colspan='" . count($range) . "' style='text-align: center;'>Tanggal " . namaBulan($bulan) . "</th>
                    <th rowspan='2' style='text-align: center;'>Total(Rp)</th>
                </tr>
                <tr>";
    foreach ($range as $day) {
        $html .= "<th style='text-align:center;'>{$day}</th>";
    }
    $html .= "</tr>
            </thead>
            <tbody>";
            foreach ($tukangList as $index => $tukang) {
                $html .= "<tr>
                            <td style='text-align:center;'>" . ($index + 1) . "</td>
                            <td>{$tukang['nama']}</td>";
                $totalBon = 0;
                foreach ($range as $day) {
                    $tanggal = "$tahun-$bulan-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $bonQuery = query(
                        "SELECT SUM(nominal) AS totalBon 
                         FROM cashbon 
                         WHERE idTukang = ? AND DATE(tanggal) = ?",
                        [$tukang['idTukang'], $tanggal]
                    );
                    $nominalBon = $bonQuery[0]['totalBon'] ?? 0;
                    $totalBon += $nominalBon;
                    $html .= "<td style='text-align:center;'>" . rupiahTanpaRp($nominalBon) . "</td>";
                }
                $html .= "<td style='text-align:center;'>" . rupiahTanpaRp($totalBon) . "</td>
                        </tr>";
            }

                        $html .= "</tbody>
                            </table>";
            }

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$filename = "Rekap Cashbon - " . namaBulan(intval($bulan)) . " {$tahun} - {$namaProyek}.pdf";
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');
$dompdf->stream($filename, ["Attachment" => true]);
exit;
?>
