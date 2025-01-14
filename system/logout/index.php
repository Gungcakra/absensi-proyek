<?php
session_start();
require_once "../../library/config.php";
checkUserSession($db);  
session_unset();
session_destroy();

if ($_SERVER['HTTP_HOST'] === 'localhost') {
    header("Location: " . BASE_URL_HTML);
} else if ($_SERVER['HTTP_HOST'] === 'gmcontractor.my.id') {
    header("Location: https://gmcontractor.my.id/");
}
// if (isset($_GET['id']) && base64_decode($_GET['id']) === session_id()) {

// }
