<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
checkUserSession($db);

function getLastAbsensi()
{
    $query = "SELECT idTukang FROM absensi ORDER BY idAbsensi DESC LIMIT 1";
    $result = query($query);
    return $result[0]['idAbsensi'];
}

function addAbsensi($idProyek, $idTukang, $tanggalAbsensi)
{

    $waktuMasuk = $tanggalAbsensi . ' 08:00:00';
    $waktuKeluar = $tanggalAbsensi . ' 17:00:00';
    $query = "INSERT INTO absensi (idProyek, idTukang, waktuMasuk, waktuKeluar, tanggal) VALUES (?, ?, ?, ?, ?)";
    return query($query, [$idProyek, $idTukang, $waktuMasuk, $waktuKeluar, $tanggalAbsensi]);
}

function deleteAbsensi($idAbsensi)
{
    $query = "DELETE FROM absensi WHERE idAbsensi = ?";
    return query($query, [$idAbsensi]);
}

function setHari($idAbsensi)
{
    $query = "UPDATE absensi SET setHari = CASE WHEN setHari = 1 THEN 0 ELSE 1 END WHERE idAbsensi = ?";
    return query($query, [$idAbsensi]);
}
function waktuMasuk($idAbsensi, $waktuMasuk)
{
    $query = "UPDATE absensi SET waktuMasuk = ? WHERE idAbsensi = ?";
    return query($query, [$waktuMasuk, $idAbsensi]);
}
function waktuKeluar($idAbsensi, $waktuKeluar)
{
    $query = "UPDATE absensi SET waktuKeluar = ? WHERE idAbsensi = ?";
    return query($query, [$waktuKeluar, $idAbsensi]);
}

if (isset($_POST['flagAbsensi'])) {
    $flagAbsensi = sanitizeInput($_POST['flagAbsensi']);
    if ($flagAbsensi === 'absensi') {
        $idTukang = sanitizeInput(data: $_POST['idTukang']);
        $idProyek = sanitizeInput($_POST['idProyek']);
        $idAbsensi = sanitizeInput($_POST['idAbsensi']);
        $tanggalAbsensi = sanitizeInput($_POST['tanggalAbsensi']) ?? date('Y-m-d');
        $tanggalHariIni = date('Y-m-d');
        if (!empty($idAbsensi)) {
            $result = deleteAbsensi($idAbsensi);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Absensi deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete Absensi: " . mysqli_error($db)];
            }
        } else {    
            $result = addAbsensi($idProyek, $idTukang, $tanggalHariIni);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "Absensi added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add Absensi."];
            }
        }
    } else if ($flagAbsensi === 'setHari') {
        $idAbsensi = sanitizeInput($_POST['idAbsensi']);
        $result = setHari($idAbsensi);
        if ($result > 0) {
            $response = ["status" => true, "pesan" => "Update Setengah Hari!"];
        } else {
            $response = ["status" => false, "pesan" => "Failed to update Hari."];
        }
    } else if ($flagAbsensi === 'waktuMasuk') {

        $idAbsensi = sanitizeInput($_POST['idAbsensi']);
        $waktuMasuk = date('Y-m-d H:i:s', strtotime(sanitizeInput($_POST['waktuMasuk'])));

        $result = waktuMasuk($idAbsensi, $waktuMasuk);
        if ($result > 0) {
            $response = ["status" => true, "pesan" => "Update Waktu Masuk!"];
        } else {
            $response = ["status" => false, "pesan" => "Failed to update Waktu Masuk."];
        }
    } else if ($flagAbsensi === 'waktuKeluar') {
        $idAbsensi = sanitizeInput($_POST['idAbsensi']);
        $waktuKeluar = date('Y-m-d H:i:s', strtotime(sanitizeInput($_POST['waktuKeluar'])));
        $result = waktuKeluar($idAbsensi, $waktuKeluar);
        if ($result > 0) {
            $response = ["status" => true, "pesan" => "Update Waktu Keluar!"];
        } else {
            $response = ["status" => false, "pesan" => "Failed to update Waktu Keluar."];
        }
    }

    echo json_encode($response);
}
