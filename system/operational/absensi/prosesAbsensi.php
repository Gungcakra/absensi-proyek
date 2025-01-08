<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function getLastAbsensi() {
    $query = "SELECT idTukang FROM absensi ORDER BY idAbsensi DESC LIMIT 1";
    $result = query($query);
    return $result[0]['idAbsensi'];
}

function addAbsensi($idProyek, $longitude, $latitude, $radius) {
    $query = "INSERT INTO absensi (idProyek, longitude, latitude, radius) VALUES (?, ?, ?, ?)";
    return query($query, [$idProyek, $longitude, $latitude, $radius]);
}

function deleteAbsensi($idAbsensi) {
    $query = "DELETE FROM absensi WHERE idAbsensi = ?";
    return query($query, [$idAbsensi]);
}

function updateAbsensi($idAbsensi, $idProyek, $longitude, $latitude, $radius) {
    $query = "UPDATE absensi SET idProyek = ?, longitude = ?, latitude = ?, radius = ? WHERE idAbsensi = ?";
    return query($query, [$idProyek, $longitude, $latitude, $radius, $idAbsensi]);
}

if (isset($_POST['flagAbsensi'])) {
    $flagAbsensi = $_POST['flagAbsensi'];
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagAbsensi) {
        case 'add':
            $idProyek = $_POST['idProyek'];
            $longitude = $_POST['longitude'];
            $latitude = $_POST['latitude'];
            $radius = $_POST['radius'];
            $result = addAbsensi($idProyek, $longitude, $latitude, $radius);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Absensi added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Absensi."];
            }
            break;

        case 'delete':
            $idAbsensi = $_POST['idAbsensi'];
            $result = deleteAbsensi($idAbsensi);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Absensi deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Absensi: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $idAbsensi = $_POST['idAbsensi'];
            $idProyek = $_POST['idProyek'];
            $longitude = $_POST['longitude'];
            $latitude = $_POST['latitude'];
            $radius = $_POST['radius'];
            $result = updateAbsensi($idAbsensi, $idProyek, $longitude, $latitude, $radius);
            if ($result) {
                $response = ["status" => true, "pesan" => "Absensi updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update Absensi: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
?>
