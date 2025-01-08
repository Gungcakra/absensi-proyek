<?php
session_start();
require_once "../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";
require_once "{$constant('BASE_URL_PHP')}/library/dateFunction.php";

//CEK USER
checkUserSession($db);

$flagAbsensi = isset($_POST['flagAbsensi']) ? $_POST['flagAbsensi'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$roleId = isset($_POST['roleId']) ? $_POST['roleId'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagAbsensi === 'cari') {

    // if (!empty($roleId)) {
    //   $searchQuery = '';
    //   $conditions .= " WHERE employees.roleId = ?";
    //   $params[] = $roleId;
    // }
    if (!empty($searchQuery)) {
        $roleId = '';
        $conditions .= " WHERE proyek.namaProyek LIKE ?";
        $params[] = "%$searchQuery%";
    }
}

// $totalQuery = "SELECT COUNT(*) as total FROM absensi INNER JOIN bidang ON absensi.idBidang = bidang.idBidang" . $conditions;
// $totalResult = query($totalQuery, $params);
// $totalRecords = $totalResult[0]['total'];
// $totalPages = ceil($totalRecords / $limit);

$query = "SELECT 
                absensi.*,
                proyek.*
          FROM absensi 
          INNER JOIN proyek ON absensi.idProyek = proyek.idProyek"
    . $conditions . " ORDER BY proyek.namaProyek ASC";


$absensi = query($query, $params);
?>

<?php if ($absensi) { ?>
    <?php foreach ($absensi as $key => $row) { ?>
        <div class="card m-2 p-3">
            <div class="row">
                <div class="col d-flex">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h12M6 4v16M6 4H5m13 0v16m0-16h1m-1 16H6m12 0h1M6 20H5M9 7h1v1H9V7Zm5 0h1v1h-1V7Zm-5 4h1v1H9v-1Zm5 0h1v1h-1v-1Zm-3 4h2a1 1 0 0 1 1 1v4h-4v-4a1 1 0 0 1 1-1Z" />
                    </svg>
                    <p class="font-size-20 p-0 m-0 font-weight-bold"><?= $row['namaProyek'] ?></p>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href="form/?data=<?= encryptUrl($row['idAbsensi']) ?>" class="font-weight-bold text-white btn btn-info mr-1">Edit</a>
                    <a class="font-weight-bold text-white btn btn-danger" onclick="deleteAbsensi('<?= $row['idAbsensi'] ?>')">Delete</a>
                </div>
            </div>
            <div class="row d-flex justify-content-start mt-1 align-items-center">
                <div class="col d-flex">
                    <p class="font-size-18"><i class="las la-calendar-alt"></i> <?= tanggalTerbilang($row['tanggalMulai']) ?></p>
                    &nbsp; <p class="font-size-18">-</p> &nbsp;
                    <p class="font-size-18"> <i class="las la-calendar-alt"></i><?= tanggalTerbilang($row['tanggalTarget']) ?></p>
                </div>
            </div>
            <button class="btn btn-light font-weight-bold mt-2" type="button" data-toggle="collapse" data-target="#collapseTable<?= $key ?>" aria-expanded="false" aria-controls="collapseTable<?= $key ?>">
                Detail Absen
            </button>
            <div class="collapse" id="collapseTable<?= $key ?>">
                <div class="card card-body d-flex justify-content-center">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $detailAbsen = query(
                                "SELECT 
                               DATE(tanggal) as tanggal, 
                               MIN(idDetailAbsensi) as idDetailAbsensi, 
                               COUNT(*) as jumlah_absen 
                           FROM 
                               detail_absensi
                           WHERE 
                               idAbsensi = ? 
                           GROUP BY 
                               DATE(tanggal) 
                           ORDER BY 
                               DATE(tanggal) ASC
                       ",
                                [$row['idAbsensi']]
                            );

                            if ($detailAbsen) {
                                foreach ($detailAbsen as $key => $rowDetail) {
                            ?>
                                    <tr>
                                        <td><?= timeStampToTanggalNamaBulan($rowDetail['tanggal']) ?></td>
                                        <td><a href="detail/?data=<?= encryptUrl($rowDetail['idDetailAbsensi']) ?>" class="btn btn-info">Detail</a></td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <div class="alert alert-warning" role="alert">
                                            <div class="iq-alert-icon">
                                                <i class="ri-alert-line"></i>
                                            </div>
                                            <div class="iq-alert-text">Data Not Found!</div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    <?php } ?>
<?php } else { ?>
    <div class="alert alert-warning mt-2" role="alert">
        <div class="iq-alert-icon">
            <i class="ri-alert-line"></i>
        </div>
        <div class="iq-alert-text">Data Not Found!</div>
    </div>
<?php } ?>