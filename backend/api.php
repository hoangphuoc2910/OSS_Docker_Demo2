<?php
// Backend/api.php
include 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"));

// 1. GET: Lấy danh sách sản phẩm
if ($method === 'GET') {
    try {
        $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([]);
    }
}

// 2. POST: Thêm sản phẩm mới
elseif ($method === 'POST') {
    if (!empty($input->name) && !empty($input->price)) {
        $sql = "INSERT INTO products (name, price, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$input->name, $input->price, $input->description ?? ''])) {
            echo json_encode(["success" => true, "message" => "Đã thêm thành công!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi khi thêm."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Thiếu tên hoặc giá!"]);
    }
}

// 3. PUT: Cập nhật (Sửa) sản phẩm
elseif ($method === 'PUT') {
    if (!empty($input->id) && !empty($input->name)) {
        $sql = "UPDATE products SET name=?, price=?, description=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute([$input->name, $input->price, $input->description ?? '', $input->id])) {
            echo json_encode(["success" => true, "message" => "Đã cập nhật!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi khi sửa."]);
        }
    }
}

// 4. DELETE: Xóa sản phẩm
elseif ($method === 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
        if ($stmt->execute([$id])) {
            echo json_encode(["success" => true, "message" => "Đã xóa!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Lỗi khi xóa."]);
        }
    }
}
