<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function getLastTukang() {
    $query = "SELECT idTukang FROM tukang ORDER BY idTukang DESC LIMIT 1";
    $result = query($query);
    return $result[0]['idTukang'];
}

function addTukang($idBidang, $nama, $alamat, $idProyek) {
    $query = "INSERT INTO tukang (idBidang, nama, alamat, idProyek) VALUES (?, ?, ?, ?)";
    return query($query, [$idBidang, $nama, $alamat, $idProyek]);
}

function deleteTukang($idTukang) {
    $query = "DELETE FROM tukang WHERE idTukang = ?";
    return query($query, [$idTukang]);
}

function updateTukang($idTukang, $idBidang, $nama, $alamat, $idProyek) {
    $query = "UPDATE tukang SET idBidang = ?, nama = ?, alamat = ?, idProyek = ? WHERE idTukang = ?";
    return query($query, [$idBidang, $nama, $alamat, $idProyek, $idTukang]);
}

if (isset($_POST['flagTukang'])) {
    $flagTukang = $_POST['flagTukang'];
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagTukang) {
        case 'add':
            $idBidang = $_POST['idBidang'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $idProyek = $_POST['idProyek'];
            $result = addTukang($idBidang, $nama, $alamat, $idProyek);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Tukang added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Tukang."];
            }
            break;

        case 'delete':
            $idTukang = $_POST['idTukang'];
            $result = deleteTukang($idTukang);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Tukang deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Tukang: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $idTukang = $_POST['idTukang'];
            $idBidang = $_POST['idBidang'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $idProyek = $_POST['idProyek'];
            $result = updateTukang($idTukang, $idBidang, $nama, $alamat, $idProyek);
            if ($result) {
                $response = ["status" => true, "pesan" => "Tukang updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update Tukang: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
?>
