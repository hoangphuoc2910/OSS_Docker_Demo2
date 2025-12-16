<?php
// Backend/config.php

// Cấu hình Header (Vẫn giữ để chuẩn JSON)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Xử lý preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- KẾT NỐI DB ---
$host = "sql203.infinityfree.com"; // Xem trong MySQL Details trên InfinityFree
$db_name = "if0_40579233_db_shop";      // Tên Database
$username = "if0_40579233";           // Username
$password = "Phuoc09087879";       // Password vPanel

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Trả về JSON lỗi nếu kết nối thất bại
    echo json_encode(["success" => false, "message" => "Lỗi kết nối DB: " . $e->getMessage()]);
    exit();
}
?>