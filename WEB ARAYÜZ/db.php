<?php
// MS SQL Server Bağlantısı
$serverName = 'localhost\SQLEXPRESS';
$database   = "AracBakimVeTakipSistemi";

$connectionInfo = [
    "Database"             => $database,
    "CharacterSet"         => "UTF-8",
    "TrustServerCertificate" => true
];

$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    $errors = sqlsrv_errors();
    die(json_encode(["error" => "Veritabanı bağlantısı başarısız: " . $errors[0]['message']]));
}

// Session başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>