<?php
session_start();
require_once "../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";

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

$namaTukang = query("SELECT nama FROM tukang WHERE idTukang = ?", [$idTukang])[0]['nama'];

?>

<h4 class="mt-2">Absensi <?= $namaTukang ?> - <?= namaBulan(intval($bulan)) ?> <?= $tahun ?></h4>

<?php
if ($rentangTanggal) {
    foreach ($rentangTanggal as $range) { ?>
        <table border="1" cellspacing="0" cellpadding="5" style="margin-top: 20px; width: 100%;">
            <thead>
                <tr>
                    <th colspan="<?= count($range) ?>" style="text-align: center;">Tanggal <?= namaBulan($bulan) ?></th>
                    <th rowspan="2" style="text-align: center;">Jml</th>
                    <th rowspan="2" style="text-align: center;">Gaji Harian</th>
                    <th rowspan="2" style="text-align: center;">Total</th>
                    <th rowspan="2" style="text-align: center;">Bon</th>
                    <th rowspan="2" style="text-align: center;">Sisa</th>
                </tr>
                <tr>
                    <?php foreach ($range as $day) { ?>
                        <th style="text-align:center;"><?= $day ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $totalBon = 0;
                    $hadirCount = 0.0;
                    foreach ($range as $day) {
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
                            [$idTukang, $tanggal]
                        );
                        $totalBon += $bonQuery[0]['totalBon'] ?? 0;
                    }

                    $gajiHarian = $tukang['gaji'] ?? 0;
                    $totalGaji = $gajiHarian * $hadirCount;
                    $sisa = $totalGaji - $totalBon;
                    ?>
                    <td style="text-align:center;"><?= $hadirCount ?></td>
                    <td style="text-align:center;"><?= rupiah($gajiHarian) ?></td>
                    <td style="text-align:center;"><?= rupiah($totalGaji) ?></td>
                    <td style="text-align:center;"><?= rupiah($totalBon) ?></td>
                    <td style="text-align:center;"><?= rupiah($sisa) ?></td>
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