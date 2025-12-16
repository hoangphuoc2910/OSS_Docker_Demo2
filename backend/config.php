<?php
// Backend/config.php

// Cấu hình Header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

// Xử lý preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// --- KẾT NỐI DB (Cấu hình cho Docker) ---
// $host phải trùng với tên service "mysql-db" trong file docker-compose.yml
$host = "mysql-db";
$db_name = "demosomee_db";
// Trong Docker, mặc định chúng ta dùng user 'root'
$username = "root";
// Password phải khớp với MYSQL_ROOT_PASSWORD trong docker-compose.yml
$password = "rootpassword";

try {
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Trả về JSON lỗi nếu kết nối thất bại
    echo json_encode(["success" => false, "message" => "Lỗi kết nối DB: " . $e->getMessage()]);
    exit();
}
