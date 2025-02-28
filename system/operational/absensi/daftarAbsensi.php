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
        $conditions .= " WHERE namaProyek LIKE ?";
        $params[] = "%$searchQuery%";
    }
}

// $totalQuery = "SELECT COUNT(*) as total FROM absensi INNER JOIN bidang ON absensi.idBidang = bidang.idBidang" . $conditions;
// $totalResult = query($totalQuery, $params);
// $totalRecords = $totalResult[0]['total'];
// $totalPages = ceil($totalRecords / $limit);

$query = "SELECT * FROM proyek" . $conditions . " ORDER BY namaProyek ASC";


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
                    <a href="detail/?data=<?= encryptUrl($row['idProyek']) ?>" class="btn btn-success">+Absen</a>
                </div>
            </div>
            <div class="row d-flex justify-content-start mt-1 align-items-center">
                <div class="col d-flex">
                    <p class="font-size-18"><i class="las la-calendar-alt"></i> <?= tanggalTerbilang($row['tanggalMulai']) ?></p>
                    &nbsp; <p class="font-size-18">-</p> &nbsp;
                    <p class="font-size-18"> <i class="las la-calendar-alt"></i><?= tanggalTerbilang($row['tanggalTarget']) ?></p>
                </div>
            </div>
            <!-- <button class="btn btn-light font-weight-bold mt-2" type="button" data-toggle="collapse" data-target="#collapseTable<?= $key ?>" aria-expanded="false" aria-controls="collapseTable<?= $key ?>">
                Detail Absen
            </button> -->
            <div class="collapse" id="collapseTable<?= $key ?>">
                <!-- <a href="laporan/?absen" class="btn-success p-1 rounded-sm text-center">Laporan Absen</a> -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Absensi = query(
                            "SELECT 
                                MIN(idAbsensi) AS idAbsensi, 
                                tanggal, 
                                GROUP_CONCAT(idAbsensi) AS idAbsensiList 
                             FROM absensi 
                             WHERE idProyek = ? 
                             GROUP BY tanggal ORDER BY tanggal DESC ",
                            [$row['idProyek']]
                        );


                        if ($Absensi) {
                            foreach ($Absensi as $key => $rowDetail) {
                        ?>
                                <tr>
                                    <td><?= timeStampToTanggalNamaBulan($rowDetail['tanggal']) ?></td>

                                    <td>
                                        <a href="detail/?absen=<?= encryptUrl($rowDetail['idAbsensi']) ?>" class="btn-primary p-1 rounded-sm">Detail</a>
                                        <!-- <div class="btn-group" role="group">
                                            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings">
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a class="dropdown-item" href="detail/?absen=<?= encryptUrl($rowDetail['idAbsensi']) ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="las la-list-ul"></i> Detail
                                                </a>
                                                <a class="dropdown-item" href="laporan/?absen=<?= encryptUrl($rowDetail['idAbsensi']) ?>" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="las la-file-alt"></i> Laporan
                                                </a>
                                            </div>
                                        </div> -->
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="2" class="text-center">
                                    <div class="alert alert-warning" role="alert">
                                        <div class="iq-alert-icon">
                                            <i class="ri-alert-line"></i>
                                        </div>
                                        <div class="iq-alert-text">Tidak Ada Data!</div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
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