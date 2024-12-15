<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}


$host = 'localhost';
$user = 'mifmyho2_exshoetic';
$password = '@Mif2024';
$dbname = 'mifmyho2_exshoetic';


$conn = new mysqli($host, $user, $password, $dbname);

?>
