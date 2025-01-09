<?php
session_start();
require_once "../../../library/config.php";
require_once "{$constant('BASE_URL_PHP')}/library/currencyFunction.php";

//CEK USER
checkUserSession($db);

$flagProyek = isset($_POST['flagProyek']) ? $_POST['flagProyek'] : '';
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : '';
$limit = isset($_POST['limit']) ? $_POST['limit'] : 10;
$page = isset($_POST['page']) ? $_POST['page'] : 1;
$offset = ($page - 1) * $limit;
$conditions = '';
$params = [];

if ($flagProyek === 'cari') {

    // if (!empty($roleId)) {
    //   $searchQuery = '';
    //   $conditions .= " WHERE employees.roleId = ?";
    //   $params[] = $roleId;
    // }
    if (!empty($searchQuery)) {
        $conditions .= " WHERE namaProyek LIKE ? OR namaPemilik LIKE ?";
        $params[] = "%$searchQuery%";
        $params[] = "%$searchQuery%";
    }
}

$totalQuery = "SELECT COUNT(*) as total FROM proyek" . $conditions;
$totalResult = query($totalQuery, $params);
$totalRecords = $totalResult[0]['total'];
$totalPages = ceil($totalRecords / $limit);

$query = "SELECT * FROM proyek "
          . $conditions . " ORDER BY namaProyek ASC LIMIT ? OFFSET ?";


$params[] = $limit;
$params[] = $offset;
$proyek = query($query, $params);
?>

<table id="proyek-list-table" class="table table-striped dataTable mt-4" role="grid"
    aria-describedby="proyek-list-page-info">
    <thead>
        <tr class="ligth">
            <th>#</th>
            <th style="min-width: 100px">Action</th>
            <th>Nama Proyek</th>
            <th>Nama Pemilik</th>
            <th>Alamat</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
       <?php if($proyek){
        ?>
         <?php foreach ($proyek as $key => $row): ?>
        <tr>
                <td><?= $key + 1 ?></td>
                <td>
                    <div class="btn-group" role="group">
                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <!-- <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Add">
                            <i class="ri-proyek-add-line mr-0"></i> Add
                        </a> -->
                        <a class="dropdown-item" href="form/?data=<?= $row['idProyek'] ?>" data-toggle="tooltip" data-placement="top" title="Edit">
                            <i class="ri-pencil-line mr-0"></i> Edit
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="tooltip" data-placement="top" title="Delete" onclick="deleteProyek(<?= $row['idProyek'] ?>)">
                            <i class="ri-delete-bin-line mr-0"></i> Delete
                        </a>
                    </div>
                    </div>
                </td>
                <td><?= $row['namaProyek'] ?></td>
                <td><?= $row['namaPemilik'] ?></td>
                <td><?= $row['alamat'] ?></td>
                <td><p class="bg-<?= $row['status']=== 'Proses' ? 'warning' : 'success' ?> p-1 rounded-sm text-white font-weight-bold text-center"><?= $row['status'] ?></p></td>
        </tr>
        
    <?php endforeach; ?>
    <?php } else { ?>
        <tr>
            <td colspan="6" class="text-center">No data found</td>
        </tr>
    <?php } ?>

    </tbody>
</table>

<div id="proyek-list-page-info" class="col-md-6" id="pagination">
    <span>Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRecords) ?> of <?= $totalRecords ?> entries</span>
</div>
<div class="col-md-6" id="pagination">
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <button class="page-link" onclick="loadPage(<?= $page - 1 ?>)">Previous</button>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <button class="page-link" onclick="loadPage(<?= $i ?>)"><?= $i ?></button>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <button class="page-link" onclick="loadPage(<?= $page + 1 ?>)">Next</button>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
