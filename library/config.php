<?php
// Database connection
require_once __DIR__ . '/./databaseConfig.php';


// Set timezone and locale
date_default_timezone_set("Asia/Jakarta");

// Define constants
define('PAGE_TITLE', value: 'Absensi');
define('BASE_URL_HTML', $_SERVER['HTTP_HOST'] === 'localhost' ? '/absensi-proyek' : '');
// define('BASE_URL_HTML', '/absensi-proyek' );
define('BASE_URL_PHP', dirname(__DIR__));

// Lambda function for concatenating constant
$constant = fn(string $name) => constant($name) ?? '';

// Query function
function query($query, $params = [])
{
    global $db;
    $stmt = mysqli_prepare($db, $query);

    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);

    $queryType = strtoupper(explode(' ', trim($query))[0]);
    if ($queryType === 'SELECT') {
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $rows;
    } else {
        $affectedRows = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
        return $affectedRows;
    }
}

// Check user session
function checkUserSession($db)
{
    // Check if session is not set
    if (!isset($_SESSION['userId']) || !isset($_SESSION['csrf_token'])) {
        session_destroy();

        // Redirect to home page based on the server host
        $redirectUrl = ($_SERVER['HTTP_HOST'] === 'localhost')
            ? BASE_URL_HTML
            : 'https://absensi-proyek.cakra-portfolio.my.id';

        header("Location: $redirectUrl");
        exit();
    }

    // Check if session is set but user is not found in database
    $query = "SELECT * FROM user WHERE userId = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_SESSION['userId']]);
    $user = $stmt->fetch();

    // Check if user is not found in database
    if (!$user) {
        session_destroy();

        // Redirect to home page based on the server host
        $redirectUrl = ($_SERVER['HTTP_HOST'] === 'localhost')
            ? BASE_URL_HTML
            : 'https://absensi-proyek.cakra-portfolio.my.id';

        header("Location: $redirectUrl");
        exit();
    }
}


// URL encryption and decryption
function encryptUrl($url)
{
    $encryptedUrl = base64_encode($url);
    $encryptedUrl = str_rot13($encryptedUrl);
    $encryptedUrl = bin2hex($encryptedUrl);
    return $encryptedUrl;
}

function decryptUrl($encryptedUrl)
{
    $encryptedUrl = hex2bin($encryptedUrl);
    $encryptedUrl = str_rot13($encryptedUrl);
    $url = base64_decode($encryptedUrl);
    return $url;
}

// Get current directory
function getCurrentDirectory()
{
    return pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
}
