<?php
session_start();
require_once "../../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";

checkUserSession($db);
$idProyek = $_GET['data'] ?? '';
$idAbsensi = $_GET['absen'] ?? '';
if ($idProyek) {
    $idProyek = decryptUrl($idProyek);
}
$idAbsensi = decryptUrl($idAbsensi);
$tanggalAbsensi = query("SELECT * FROM absensi WHERE idAbsensi = ?", [$idAbsensi])[0]['tanggal'];
$idProyek = query("SELECT * FROM absensi WHERE idAbsensi = ?", [$idAbsensi])[0]['idProyek'];
$namaProyek = query("SELECT namaProyek FROM proyek WHERE idProyek = ?", [$idProyek])[0]['namaProyek'];

// Generate date ranges for the three tables
$rentangTanggal = [
    range(1, 10),
    range(11, 20),
    range(21, (int)date('t', strtotime($tanggalAbsensi)))
];

// Fetch all absences for the month for the project
$absensiBulan = query(
    "SELECT absensi.tanggal, absensi.idTukang FROM absensi WHERE absensi.idProyek = ? AND MONTH(absensi.tanggal) = MONTH(?) AND YEAR(absensi.tanggal) = YEAR(?)",
    [$idProyek, $tanggalAbsensi, $tanggalAbsensi]
);

$absensiMap = [];
foreach ($absensiBulan as $absen) {
    $absensiMap[$absen['tanggal']][$absen['idTukang']] = true;
}

// Fetch all workers for the project
$tukangList = query("SELECT * FROM tukang WHERE idProyek = ?", [$idProyek]);
?>

<style>
    h4 {
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
    }

    table {
        border: 1px solid black;
        text-align: left;
        border-collapse: collapse;
        width: 100%;
        margin-bottom: 100px;
    }

    table thead th,
    table tbody td {
        border: 1px solid black;
        text-align: left;
        padding: 8px;
    }
</style>

<h4><?= $namaProyek ?></h4>

<?php foreach ($rentangTanggal as $index => $range) { ?>
    <table class="table table-striped dataTable mt-4" role="grid" aria-describedby="tukang-list-page-info">
        <thead>
            <tr>
                <th rowspan="2" style="text-align: center;">Nama</th>
                <th colspan="<?= count($range) ?>" style="text-align: center;">Bulan <?= dateToMonthName($tanggalAbsensi) ?> / Tanggal <?= implode('-', [$range[0], end($range)]) ?></th>
                <th rowspan="2" style="text-align: center;">Jml</th>
                <th rowspan="2" style="text-align: center;">Gaji Harian</th>
                <th rowspan="2" style="text-align: center;">Total</th>
                <th rowspan="2" style="text-align: center;">Bon</th>
                <th rowspan="2" style="text-align: center;">Sisa</th>
            </tr>
            <tr>
                <?php foreach ($range as $day) { ?>
                    <th style="text-align:center;">
                        <?= $day ?>
                    </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tukangList as $tukang) { ?>
                <tr>
                    <td><?= $tukang['nama'] ?></td>
                    <?php
                    $hadirCount = 0;
                    foreach ($range as $day) {
                        $tanggal = date('Y-m-', strtotime($tanggalAbsensi)) . str_pad($day, 2, '0', STR_PAD_LEFT);
                        $status = isset($absensiMap[$tanggal][$tukang['idTukang']]) ? 'Hadir' : 'Tidak Hadir';
                        if ($status === 'Hadir') {
                            $hadirCount++;
                        }
                        echo "<td style=\"text-align:center;\">$status</td>";
                    }
                    $gajiHarian = $tukang['gaji'];
                    $totalGaji = $gajiHarian * $hadirCount;
                    ?>
                    <td style="text-align:center;"><?= $hadirCount ?></td>
                    <td style="text-align:center;"><?= rupiah($gajiHarian) ?></td>
                    <td style="text-align:center;"><?= rupiah($totalGaji) ?></td>
                    <td style="text-align:center;">Bon Placeholder</td>
                    <td style="text-align:center;">Sisa Placeholder</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>