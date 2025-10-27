<?php
include '../conection.php';
session_start();

if (!isset($_SESSION['maKhachHang'])) {
    header("location: ../user/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ContinueCartPay'])) {

    $maKhachHang = $_SESSION['maKhachHang'];
    $tenKhachHang = $_POST['tenKhachHang'] ?? '';
    $diaChi = $_POST['diaChi'] ?? '';
    $soDienThoai = $_POST['soDienThoai'] ?? '';
    $phuongThucThanhToan = isset($_POST['payment']) ? (int)$_POST['payment'] : 2;
    if ($phuongThucThanhToan !== 1 && $phuongThucThanhToan !== 2) {
        $phuongThucThanhToan = 2;
    }

    $ThoiGianLap = date("Y-m-d H:i:s");
    $trangThaiDonHang = 0;
    $maNhanVien = 1;
    $ghiChu = "Khong co";

    // Tính tổng tiền từ giỏ
    $allAmount = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $value) {
            foreach ($value as $data) {
                $allAmount += ((int)$data['qty']) * ((int)$data['giaBan']);
            }
        }
    }

    // Lưu đơn hàng
    $sql_saveOrder = "INSERT INTO donhang(
        maKhachHang, maNhanVien, ghiChu, tongGia, thoiGian, trangThaiDonHang, phuongThucThanhToan
    ) VALUES (
        '".$maKhachHang."', '".$maNhanVien."', '".$ghiChu."', '".$allAmount."', '".$ThoiGianLap."', '".$trangThaiDonHang."', '".$phuongThucThanhToan."'
    )";
    mysqli_query($mysqli, $sql_saveOrder);

    // Lấy id đơn hàng đúng chuẩn
    $maDonHang = mysqli_insert_id($mysqli);

    // Lưu chi tiết
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $value) {
            foreach ($value as $data) {
                $id  = (int)$data['id'];
                $ten = mysqli_real_escape_string($mysqli, $data['tenSanPham']);
                $gia = (int)$data['giaBan'];
                $qty = (int)$data['qty'];

                $sql_saveOrderDetail = "INSERT INTO chitietdonhang(maDonhang, maSanPham, tenSanPham, soLuong, giaSanPham)
                                        VALUES('".$maDonHang."', '".$id."', '".$ten."', '".$qty."', '".$gia."')";
                mysqli_query($mysqli, $sql_saveOrderDetail);
            }
        }
    }

    // Xoá giỏ + chuyển đến payDone
    unset($_SESSION['cart']);
    header("Location: payDone.php?id=" . $maDonHang);
    exit;
}

// fallback
header("Location: index.php?errCode=1");
exit;
