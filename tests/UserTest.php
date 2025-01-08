<?php
require_once __DIR__ . "/../system/data/user/prosesUser.php";
$db = mysqli_connect("localhost", "root", "", "templatedb");

function measureExecutionTime($callback) {
    $startTime = microtime(true);
    $callback();
    $endTime = microtime(true);
    return $endTime - $startTime;
}

$functionCount = 0;

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

$lastId = getLastUser();
$functionCount++;

echo "\nTesting Update User...\n";
$userId = 1;
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

echo "\nTesting Delete User...\n";
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

echo "\nTotal functions executed: {$functionCount}\n";
