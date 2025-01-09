<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function getLastCashbon() {
    $query = "SELECT idCashbon FROM cashbon ORDER BY idCashbon DESC LIMIT 1";
    $result = query($query);
    return $result[0]['idCashbon'];
}

function addCashbon($idTukang, $keterangan, $nominal) {
    $query = "INSERT INTO cashbon (idTukang, keterangan, nominal) VALUES (?, ?, ?)";
    return query($query, [$idTukang, $keterangan, $nominal]);
}

function deleteCashbon($idCashbon) {
    $query = "DELETE FROM cashbon WHERE idCashbon = ?";
    return query($query, [$idCashbon]);
}

function updateCashbon($idCashbon, $idTukang, $keterangan, $nominal) {
    $query = "UPDATE cashbon SET idTukang = ?, keterangan = ?, nominal = ? WHERE idCashbon = ?";
    return query($query, [$idTukang, $keterangan, $nominal, $idCashbon]);
}

if (isset($_POST['flagCashbon'])) {
    $flagCashbon = $_POST['flagCashbon'];
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagCashbon) {
        case 'add':
            $idTukang = $_POST['idTukang'];
            $keterangan = $_POST['keterangan'];
            $nominal = $_POST['nominal'];
            $result = addCashbon($idTukang, $keterangan, $nominal);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Cashbon added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Cashbon."];
            }
            break;

        case 'delete':
            $idCashbon = $_POST['idCashbon'];
            $result = deleteCashbon($idCashbon);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Cashbon deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Cashbon: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $idCashbon = $_POST['idCashbon'];
            $idTukang = $_POST['idTukang'];
            $keterangan = $_POST['keterangan'];
            $nominal = $_POST['nominal'];
            $result = updateCashbon($idCashbon, $idTukang, $keterangan, $nominal);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Cashbon updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update Cashbon: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
