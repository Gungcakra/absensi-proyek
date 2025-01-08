<?php
session_start();
require_once __DIR__ . "/../../../library/config.php";
function getLastUser() {
    $query = "SELECT userId FROM user ORDER BY userId DESC LIMIT 1";
    $result = query($query);
    return $result[0]['userId'];
}
function addUser($username, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO user (username, password) VALUES (?, ?)";
    return query($query, [$username, $hashedPassword]);
}

function deleteUser($userId) {
    $query = "DELETE FROM user WHERE userId = ?";
    return query($query, [$userId]);
}

function updateUser($userId, $username, $password = null) {
    if ($password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE user SET username = ?, password = ? WHERE userId = ?";
        return query($query, [$username, $hashedPassword, $userId]);
    } else {
        $query = "UPDATE user SET username = ? WHERE userId = ?";
        return query($query, [$username, $userId]);
    }
}

if (isset($_POST['flagUser'])) {
    $flagUser = $_POST['flagUser'];
    $response = ["status" => false, "pesan" => "Invalid action"];

    switch ($flagUser) {
        case 'add':
            $username = $_POST['username'];
            $password = $_POST['password'];
            $result = addUser($username, $password);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "User added successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to add User."];
            }
            break;

        case 'delete':
            $userId = $_POST['userId'];
            $result = deleteUser($userId);
            if ($result > 0) {
                $response = ["status" => true, "pesan" => "User deleted successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to delete User: " . mysqli_error($db)];
            }
            break;

        case 'update':
            $userId = $_POST['idUser'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $result = updateUser($userId, $username, $password);
            if ($result) {
                $response = ["status" => true, "pesan" => "User updated successfully!"];
            } else {
                $response = ["status" => false, "pesan" => "Failed to update User: " . mysqli_error($db)];
            }
            break;
    }

    echo json_encode($response);
}
?>
