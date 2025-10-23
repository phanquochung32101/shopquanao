<?php
// C:\xampp\htdocs\shopquanao\support\submit.php
require_once("../conection.php");
session_start();

function redirect_with($params){
  $qs = http_build_query($params);
  header("Location: index.php?$qs");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  redirect_with(['err' => 'Phương thức không hợp lệ.']);
}

/* Bắt buộc phải có cookie phiên */
if (session_id() === '' || !isset($_COOKIE[session_name()])) {
  redirect_with(['err' => 'Phiên không có cookie. Vui lòng bật cookie và thử lại.']);
}

/* Kiểm tra CSRF */
if (
  empty($_POST['csrf']) ||
  empty($_SESSION['csrf']) ||
  !hash_equals($_SESSION['csrf'], $_POST['csrf'])
) {
  redirect_with(['err' => 'Phiên không hợp lệ, hãy thử lại.']);
}


$tieu_de   = trim($_POST['tieu_de'] ?? '');
$ten       = trim($_POST['ten_khach_hang'] ?? '');
$ma        = trim($_POST['ma_khach_hang'] ?? '');
$noi_dung  = trim($_POST['noi_dung_can_ho_tro'] ?? '');
$vat_pham  = trim($_POST['vat_pham_can_ho_tro'] ?? '');
$email     = trim($_POST['email'] ?? '');

if ($tieu_de === '' || $ten === '' || $noi_dung === '' || $email === '') {
  redirect_with(['err' => 'Vui lòng nhập đầy đủ các trường bắt buộc.']);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  redirect_with(['err' => 'Email không hợp lệ.']);
}

// Ensure table exists (safe if already created)
$createSql = "CREATE TABLE IF NOT EXISTS support_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tieuDe VARCHAR(255) NOT NULL,
  tenKhachHang VARCHAR(100) NOT NULL,
  maKhachHang VARCHAR(50) NULL,
  noiDung TEXT NOT NULL,
  vatPham VARCHAR(255) NULL,
  email VARCHAR(150) NOT NULL,
  status TINYINT(1) NOT NULL DEFAULT 0, -- 0: chờ xử lý, 1: đã xử lý
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($mysqli, $createSql);


// Insert using prepared statement
$stmt = mysqli_prepare($mysqli, "INSERT INTO support_requests (tieuDe, tenKhachHang, maKhachHang, noiDung, vatPham, email) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
  redirect_with(['err' => 'Không thể chuẩn bị truy vấn.']);
}
mysqli_stmt_bind_param($stmt, "ssssss", $tieu_de, $ten, $ma, $noi_dung, $vat_pham, $email);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$ok) {
  redirect_with(['err' => 'Lưu yêu cầu thất bại.']);
}

unset($_SESSION['csrf']);
redirect_with(['ok' => 1]);
