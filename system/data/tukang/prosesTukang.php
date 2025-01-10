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
    $flagTukang = sanitizeInput($_POST['flagTukang']);
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagTukang) {
        case 'add':
            $idProyek = sanitizeInput($_POST['idProyek']);
            $nama = sanitizeInput($_POST['nama']);
            $alamat = sanitizeInput($_POST['alamat']);
            $telp = sanitizeInput($_POST['telp']);
            $bidang = sanitizeInput($_POST['bidang']);
            $jenis = sanitizeInput($_POST['jenis']);
            $gaji = sanitizeInput($_POST['gaji']);
            $result = addTukang($idProyek, $nama, $alamat, $telp, $bidang, $jenis, $gaji);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Tukang added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Tukang."];
            }
            break;

        case 'delete':
            $idTukang = sanitizeInput($_POST['idTukang']);
            $result = deleteTukang($idTukang);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Tukang deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Tukang: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $idTukang = sanitizeInput($_POST['idTukang']);
            $idProyek = sanitizeInput($_POST['idProyek']);
            $nama = sanitizeInput($_POST['nama']);
            $alamat = sanitizeInput($_POST['alamat']);
            $telp = sanitizeInput($_POST['telp']);
            $bidang = sanitizeInput($_POST['bidang']);
            $jenis = sanitizeInput($_POST['jenis']);
            $gaji = sanitizeInput($_POST['gaji']);
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
