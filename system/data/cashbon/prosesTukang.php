<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function getLastTukang() {
    $query = "SELECT idTukang FROM tukang ORDER BY idTukang DESC LIMIT 1";
    $result = query($query);
    return $result[0]['idTukang'];
}

function addTukang($idProyek, $nama, $alamat, $telp, $bidang, $jenis, $gaji) {
    $query = "INSERT INTO tukang (idProyek, nama, alamat, telp, bidang, jenis, gaji) VALUES (?, ?, ?, ?, ?, ?, ?)";
    return query($query, [$idProyek, $nama, $alamat, $telp, $bidang, $jenis, $gaji]);
}

function deleteTukang($idTukang) {
    $query = "DELETE FROM tukang WHERE idTukang = ?";
    return query($query, [$idTukang]);
}

function updateTukang($idTukang, $idProyek, $nama, $alamat, $telp, $bidang, $jenis, $gaji) {
    $query = "UPDATE tukang SET idProyek = ?, nama = ?, alamat = ?, telp = ?, bidang = ?, jenis = ?, gaji = ? WHERE idTukang = ?";
    return query($query, [$idProyek, $nama, $alamat, $telp, $bidang, $jenis, $gaji, $idTukang]);
}

if (isset($_POST['flagTukang'])) {
    $flagTukang = $_POST['flagTukang'];
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagTukang) {
        case 'add':
            $idProyek = $_POST['idProyek'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $telp = $_POST['telp'];
            $bidang = $_POST['bidang'];
            $jenis = $_POST['jenis'];
            $gaji = $_POST['gaji'];
            $result = addTukang($idProyek, $nama, $alamat, $telp, $bidang, $jenis, $gaji);
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
            $idProyek = $_POST['idProyek'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $telp = $_POST['telp'];
            $bidang = $_POST['bidang'];
            $jenis = $_POST['jenis'];
            $gaji = $_POST['gaji'];
            $result = updateTukang($idTukang, $idProyek, $nama, $alamat, $telp, $bidang, $jenis, $gaji);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Tukang updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update Tukang: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
