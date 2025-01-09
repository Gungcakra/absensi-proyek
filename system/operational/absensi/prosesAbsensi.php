<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function getLastAbsensi() {
    $query = "SELECT idTukang FROM absensi ORDER BY idAbsensi DESC LIMIT 1";
    $result = query($query);
    return $result[0]['idAbsensi'];
}

function addAbsensi($idProyek, $idTukang) {
    $tanggal = date('Y-m-d H:i:s');
    $query = "INSERT INTO absensi (idProyek, idTukang, tanggal) VALUES (?, ?, ?)";
    return query($query, [$idProyek, $idTukang, $tanggal]);
}

function deleteAbsensi($idAbsensi) {
    $query = "DELETE FROM absensi WHERE idAbsensi = ?";
    return query($query, [$idAbsensi]);
}
function setHari($idAbsensi) {
    $query = "UPDATE absensi SET setHari = CASE WHEN setHari = 1 THEN 0 ELSE 1 END WHERE idAbsensi = ?";
    return query($query, [$idAbsensi]);
}


if (isset($_POST['flagAbsensi'])) {
    $flagAbsensi = $_POST['flagAbsensi'];
    if ($flagAbsensi === 'absensi') {
        $idTukang = $_POST['idTukang'];
        $idProyek = $_POST['idProyek'];
        $idAbsensi = $_POST['idAbsensi'];
        
        if (!empty($idAbsensi)) {
            $result = deleteAbsensi($idAbsensi);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Absensi deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Absensi: " . mysqli_error($db)];
            }
        } else {
            $result = addAbsensi($idProyek, $idTukang);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Absensi added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Absensi."];
            }
        }
    } else if ($flagAbsensi === 'setHari') {
        $idAbsensi = $_POST['idAbsensi'];
        $result = setHari($idAbsensi);
        if ($result > 0) {
            $response = ["status" => true, "pesan" => "Update Setengah Hari!"];
        } else {
            $response = ["status" => false, "pesan" => "Failed to update Hari."];
        }
    }

    echo json_encode($response);
}
?>
