<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function addBidang($jenis, $tipe, $gaji) {
    $query = "INSERT INTO bidang (jenis, tipe, gaji) VALUES (?, ?, ?)";
    return query($query, [$jenis, $tipe, $gaji]);
}

function deleteBidang($idBidang) {
    $query = "DELETE FROM bidang WHERE idBidang = ?";
    return query($query, [$idBidang]);
}

function updateBidang($idBidang, $jenis, $tipe, $gaji) {
    $query = "UPDATE bidang SET jenis = ?, tipe = ?, gaji = ? WHERE idBidang = ?";
    return query($query, [$jenis, $tipe, $gaji, $idBidang]);
}

if (isset($_POST['flagBidang'])) {
    $flagBidang = $_POST['flagBidang'];
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagBidang) {
        case 'add':
            $jenis = $_POST['jenis'];
            $tipe = $_POST['tipe'];
            $gaji = $_POST['gaji'];
            $result = addBidang($jenis, $tipe, $gaji);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Bidang added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Bidang."];
            }
            break;

        case 'delete':
            $idBidang = $_POST['idBidang'];
            $result = deleteBidang($idBidang);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Bidang deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Bidang: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $idBidang = $_POST['idBidang'];
            $jenis = $_POST['jenis'];
            $tipe = $_POST['tipe'];
            $gaji = $_POST['gaji'];
            $result = updateBidang($idBidang, $jenis, $tipe, $gaji);
            if ($result) {
                $response = ["status" => true, "pesan" => "Bidang updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update Bidang: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
?>
