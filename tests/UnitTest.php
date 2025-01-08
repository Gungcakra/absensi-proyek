<?php
require_once __DIR__ . "/../system/data/user/prosesUser.php";
$db = mysqli_connect("localhost", "root", "", "templatedb");
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

function measureExecutionTime($callback) {
    $startTime = microtime(true);
    $callback();
    $endTime = microtime(true);
    return $endTime - $startTime;
}

$functionCount = 0;

function testAddUser() {
    global $functionCount;
    echo "Testing Add User...\n";
    $username = 'testuser';
    $password = 'testpassword';
    $executionTime = measureExecutionTime(function() use ($username, $password, &$functionCount) {
        $result = addUser($username, $password);
        $functionCount++;
        if ($result > 0) {
            echo "User added successfully!\n";
        } else {
            echo "Failed to add User.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testUpdateUser() {
    global $functionCount;
    echo "\nTesting Update User...\n";
    $lastId = getLastUser();
    $functionCount++;
    $newUsername = 'updateduser';
    $newPassword = 'newpassword';
    $executionTime = measureExecutionTime(function() use ($lastId, $newUsername, $newPassword, &$functionCount) {
        $result = updateUser($lastId, $newUsername, $newPassword);
        $functionCount++;
        if ($result) {
            echo "User updated successfully!\n";
        } else {
            echo "Failed to update User.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testDeleteUser() {
    global $functionCount;
    echo "\nTesting Delete User...\n";
    $lastId = getLastUser();
    $functionCount++;
    $executionTime = measureExecutionTime(function() use ($lastId, &$functionCount) {
        $result = deleteUser($lastId);
        $functionCount++;
        if ($result > 0) {
            echo "User deleted successfully!\n";
        } else {
            echo "Failed to delete User.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

testAddUser();
testUpdateUser();
testDeleteUser();

echo "\nTotal functions executed: {$functionCount}\n";
?>
function testAddTukang() {
    global $functionCount;
    echo "\nTesting Add Tukang...\n";
    $name = 'testtukang';
    $executionTime = measureExecutionTime(function() use ($name, &$functionCount) {
        $result = addTukang($name);
        $functionCount++;
        if ($result > 0) {
            echo "Tukang added successfully!\n";
        } else {
            echo "Failed to add Tukang.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testUpdateTukang() {
    global $functionCount;
    echo "\nTesting Update Tukang...\n";
    $lastId = getLastTukang();
    $functionCount++;
    $newName = 'updatedtukang';
    $executionTime = measureExecutionTime(function() use ($lastId, $newName, &$functionCount) {
        $result = updateTukang($lastId, $newName);
        $functionCount++;
        if ($result) {
            echo "Tukang updated successfully!\n";
        } else {
            echo "Failed to update Tukang.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testDeleteTukang() {
    global $functionCount;
    echo "\nTesting Delete Tukang...\n";
    $lastId = getLastTukang();
    $functionCount++;
    $executionTime = measureExecutionTime(function() use ($lastId, &$functionCount) {
        $result = deleteTukang($lastId);
        $functionCount++;
        if ($result > 0) {
            echo "Tukang deleted successfully!\n";
        } else {
            echo "Failed to delete Tukang.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testAddBidang() {
    global $functionCount;
    echo "\nTesting Add Bidang...\n";
    $name = 'testbidang';
    $executionTime = measureExecutionTime(function() use ($name, &$functionCount) {
        $result = addBidang($name);
        $functionCount++;
        if ($result > 0) {
            echo "Bidang added successfully!\n";
        } else {
            echo "Failed to add Bidang.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testUpdateBidang() {
    global $functionCount;
    echo "\nTesting Update Bidang...\n";
    $lastId = getLastBidang();
    $functionCount++;
    $newName = 'updatedbidang';
    $executionTime = measureExecutionTime(function() use ($lastId, $newName, &$functionCount) {
        $result = updateBidang($lastId, $newName);
        $functionCount++;
        if ($result) {
            echo "Bidang updated successfully!\n";
        } else {
            echo "Failed to update Bidang.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testDeleteBidang() {
    global $functionCount;
    echo "\nTesting Delete Bidang...\n";
    $lastId = getLastBidang();
    $functionCount++;
    $executionTime = measureExecutionTime(function() use ($lastId, &$functionCount) {
        $result = deleteBidang($lastId);
        $functionCount++;
        if ($result > 0) {
            echo "Bidang deleted successfully!\n";
        } else {
            echo "Failed to delete Bidang.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testAddProyek() {
    global $functionCount;
    echo "\nTesting Add Proyek...\n";
    $name = 'testproyek';
    $executionTime = measureExecutionTime(function() use ($name, &$functionCount) {
        $result = addProyek($name);
        $functionCount++;
        if ($result > 0) {
            echo "Proyek added successfully!\n";
        } else {
            echo "Failed to add Proyek.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testUpdateProyek() {
    global $functionCount;
    echo "\nTesting Update Proyek...\n";
    $lastId = getLastProyek();
    $functionCount++;
    $newName = 'updatedproyek';
    $executionTime = measureExecutionTime(function() use ($lastId, $newName, &$functionCount) {
        $result = updateProyek($lastId, $newName);
        $functionCount++;
        if ($result) {
            echo "Proyek updated successfully!\n";
        } else {
            echo "Failed to update Proyek.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testDeleteProyek() {
    global $functionCount;
    echo "\nTesting Delete Proyek...\n";
    $lastId = getLastProyek();
    $functionCount++;
    $executionTime = measureExecutionTime(function() use ($lastId, &$functionCount) {
        $result = deleteProyek($lastId);
        $functionCount++;
        if ($result > 0) {
            echo "Proyek deleted successfully!\n";
        } else {
            echo "Failed to delete Proyek.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testAddAbsensi() {
    global $functionCount;
    echo "\nTesting Add Absensi...\n";
    $userId = 1; // Assuming user ID 1 exists
    $proyekId = 1; // Assuming proyek ID 1 exists
    $date = '2023-10-01';
    $executionTime = measureExecutionTime(function() use ($userId, $proyekId, $date, &$functionCount) {
        $result = addAbsensi($userId, $proyekId, $date);
        $functionCount++;
        if ($result > 0) {
            echo "Absensi added successfully!\n";
        } else {
            echo "Failed to add Absensi.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testUpdateAbsensi() {
    global $functionCount;
    echo "\nTesting Update Absensi...\n";
    $lastId = getLastAbsensi();
    $functionCount++;
    $newDate = '2023-10-02';
    $executionTime = measureExecutionTime(function() use ($lastId, $newDate, &$functionCount) {
        $result = updateAbsensi($lastId, $newDate);
        $functionCount++;
        if ($result) {
            echo "Absensi updated successfully!\n";
        } else {
            echo "Failed to update Absensi.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

function testDeleteAbsensi() {
    global $functionCount;
    echo "\nTesting Delete Absensi...\n";
    $lastId = getLastAbsensi();
    $functionCount++;
    $executionTime = measureExecutionTime(function() use ($lastId, &$functionCount) {
        $result = deleteAbsensi($lastId);
        $functionCount++;
        if ($result > 0) {
            echo "Absensi deleted successfully!\n";
        } else {
            echo "Failed to delete Absensi.\n";
        }
    });
    echo "Execution time: {$executionTime} seconds\n";
}

testAddTukang();
testUpdateTukang();
testDeleteTukang();
testAddBidang();
testUpdateBidang();
testDeleteBidang();
testAddProyek();
testUpdateProyek();
testDeleteProyek();
testAddAbsensi();
testUpdateAbsensi();
testDeleteAbsensi();
