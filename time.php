<?php
// 1. Cấu hình CORS (Để frontend ở InfinityFree gọi được vào)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Xử lý request OPTIONS (Preflight) của trình duyệt
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 2. Thông tin kết nối SQL Server (Lấy từ project cũ)
$serverName = "DemoDBSomee.mssql.somee.com";
$database = "DemoDBSomee";
$uid = "hoangphuoc_SQLLogin_1";
$pwd = "l7fgqcxbx1";

try {
    // Kết nối sử dụng PDO (Chuẩn mới nhất)
    $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $uid, $pwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Nếu lỗi kết nối DB thì báo ngay
    http_response_code(500);
    echo json_encode(["message" => "Lỗi kết nối CSDL: " . $e->getMessage()]);
    exit();
}

// 3. Xử lý Logic
$method = $_SERVER['REQUEST_METHOD'];

// --- CHỨC NĂNG 1: KIỂM TRA TRẠNG THÁI (GET) ---
if ($method === 'GET') {
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM VisitLogs");
        $totalVisits = $stmt->fetchColumn();

        echo json_encode([
            "serverTime" => date("d/m/Y H:i:s"), // Format ngày giờ Việt Nam
            "totalChecks" => $totalVisits
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["message" => $e->getMessage()]);
    }
}

// --- CHỨC NĂNG 2: GỬI ĐÁNH GIÁ (POST) ---
elseif ($method === 'POST') {
    try {
        // Lấy dữ liệu JSON gửi lên
        $input = json_decode(file_get_contents("php://input"), true);

        $name = isset($input['name']) ? $input['name'] : null;
        $dob = isset($input['dob']) && $input['dob'] !== "" ? $input['dob'] : null;
        $rating = isset($input['rating']) ? $input['rating'] : 0;
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        // Câu lệnh SQL Insert (Dùng GETDATE() thay cho DateTime.Now của C#)
        $sql = "INSERT INTO VisitLogs (VisitTime, UserAgent, VisitorName, VisitorDOB, Rating) 
                VALUES (GETDATE(), ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$agent, $name, $dob, $rating]);

        echo json_encode([
            "success" => true,
            "data" => [
                "name" => $name,
                "rating" => $rating,
                "time" => date("H:i:s")
            ]
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
