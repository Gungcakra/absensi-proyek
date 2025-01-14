<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);



function getLastCashbon() {
    $query = "SELECT idCashbon FROM cashbon ORDER BY idCashbon DESC LIMIT 1";
    $result = query($query);
    return $result[0]['idCashbon'];
}

function addCashbon($idTukang, $keterangan, $nominal, $tanggal) {
    $query = "INSERT INTO cashbon (idTukang, keterangan, nominal, tanggal) VALUES (?, ?, ?, ?)";
    return query($query, [$idTukang, $keterangan, $nominal, $tanggal]);
}

function deleteCashbon($idCashbon) {
    $query = "DELETE FROM cashbon WHERE idCashbon = ?";
    return query($query, [$idCashbon]);
}

function updateCashbon($idCashbon, $idTukang, $keterangan, $nominal, $tanggal) {
    $query = "UPDATE cashbon SET idTukang = ?, keterangan = ?, nominal = ?, tanggal = ? WHERE idCashbon = ?";
    return query($query, [$idTukang, $keterangan, $nominal, $tanggal, $idCashbon]);
}

if (isset($_POST['flagCashbon'])) {
    $flagCashbon = sanitizeInput($_POST['flagCashbon']);
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagCashbon) {
        case 'add':
            $idTukang = sanitizeInput($_POST['idTukang']);
            $keterangan = sanitizeInput($_POST['keterangan']);
            $nominal = sanitizeInput($_POST['nominal']);
            $tanggal = date('Y-m-d H:i:s', strtotime(sanitizeInput($_POST['tanggal'])));
            $result = addCashbon($idTukang, $keterangan, $nominal, $tanggal);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Cashbon added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Cashbon."];
            }
            break;

        case 'delete':
            $idCashbon = sanitizeInput($_POST['idCashbon']);
            $result = deleteCashbon($idCashbon);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Cashbon deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Cashbon: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $idCashbon = sanitizeInput($_POST['idCashbon']);
            $idTukang = sanitizeInput($_POST['idTukang']);
            $keterangan = sanitizeInput($_POST['keterangan']);
            $nominal = sanitizeInput($_POST['nominal']);
            $tanggal = sanitizeInput($_POST['tanggal']);
            $tanggal = date('Y-m-d H:i:s', strtotime(sanitizeInput($_POST['tanggal'])));
            $result = updateCashbon($idCashbon, $idTukang, $keterangan, $nominal, $tanggal);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Cashbon updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update Cashbon: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
?>
