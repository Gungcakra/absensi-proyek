<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function addProyek($namaProyek, $namaPemilik, $tanggalMulai, $tanggalTarget, $alamat, $status) {
    $getLastId = query("SELECT IFNULL(MAX(idProyek), 0) AS lastId FROM proyek",[]);
    $newId = $getLastId[0]['lastId'] + 1;
    $query = "INSERT INTO proyek (idProyek, namaProyek, namaPemilik, tanggalMulai, tanggalTarget, alamat, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
    return query($query, [$newId, $namaProyek, $namaPemilik, $tanggalMulai, $tanggalTarget, $alamat, $status]);
}

function deleteProyek($idProyek) {
    $query = "DELETE FROM proyek WHERE idProyek = ?";
    return query($query, [$idProyek]);
}

function updateProyek($idProyek, $namaProyek, $namaPemilik, $tanggalMulai, $tanggalTarget, $alamat, $status) {
    $query = "UPDATE proyek SET namaProyek = ?, namaPemilik = ?, tanggalMulai = ?, tanggalTarget = ?, alamat = ?, status = ? WHERE idProyek = ?";
    return query($query, [$namaProyek, $namaPemilik, $tanggalMulai, $tanggalTarget, $alamat, $status, $idProyek]);
}

if (isset($_POST['flagProyek'])) {
    $flagProyek = sanitizeInput($_POST['flagProyek']);
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagProyek) {
        case 'add':
            $namaProyek = sanitizeInput($_POST['namaProyek']);
            $namaPemilik = sanitizeInput($_POST['namaPemilik']);
            $tanggalMulai = sanitizeInput($_POST['tanggalMulai']);
            $tanggalTarget = sanitizeInput($_POST['tanggalTarget']);
            $alamat = sanitizeInput($_POST['alamat']);
            $status = sanitizeInput($_POST['status']);
            $result = addProyek($namaProyek, $namaPemilik, $tanggalMulai, $tanggalTarget, $alamat, $status);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Proyek added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Proyek."];
            }
            break;

        case 'delete':
            $idProyek = sanitizeInput($_POST['idProyek']);
            $result = deleteProyek($idProyek);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Proyek deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Proyek: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $idProyek = sanitizeInput($_POST['idProyek']);
            $namaProyek = sanitizeInput($_POST['namaProyek']);
            $namaPemilik = sanitizeInput($_POST['namaPemilik']);
            $tanggalMulai = sanitizeInput($_POST['tanggalMulai']);
            $tanggalTarget = sanitizeInput($_POST['tanggalTarget']);
            $alamat = sanitizeInput($_POST['alamat']);
            $status = sanitizeInput($_POST['status']);
            $result = updateProyek($idProyek, $namaProyek, $namaPemilik, $tanggalMulai, $tanggalTarget, $alamat, $status);
            if ($result) {
                $response = ["status" => true, "pesan" => "Proyek updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update Proyek: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
?>
