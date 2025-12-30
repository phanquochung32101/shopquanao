<?php
include("../../conection.php");
session_start();

if (!isset($_SESSION['maQuanLy'])) {
  header('location:login.php');
  exit();
}

$id = $_SESSION['maQuanLy'];
$sql_quanly = "SELECT * FROM quanly WHERE maQuanLy = $id LIMIT 1";
$query_quanly = mysqli_query($mysqli, $sql_quanly);
$row_quanly = mysqli_fetch_array($query_quanly);

// Lấy toàn bộ nhân viên
$sql_getAllEmp = "SELECT * FROM nhanvien ORDER BY maNhanVien DESC";
$query_getAllEmp = mysqli_query($mysqli, $sql_getAllEmp);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Quản lý nhân viên</title>

  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?php include("navbar.php"); ?>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../index.php" class="brand-link">
      <img src="../dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light" style="font-size:17px;">
        <?php echo htmlspecialchars($row_quanly['tenQuanLy'], ENT_QUOTES, 'UTF-8'); ?>
      </span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column">

          <a href="../index.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Thống Kê</p>
          </a>

          <a href="product.php" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>Quản Lý Sản Phẩm</p>
          </a>

          <a href="category.php" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>Quản Lý Danh Mục</p>
          </a>

          <a href="bills.php" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>Quản Lý Hóa Đơn</p>
          </a>

          <a href="users.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Quản Lý Khách Hàng</p>
          </a>

          <!-- ACTIVE -->
          <a href="employee.php" class="nav-link active">
            <i class="nav-icon fas fa-user-tie"></i>
            <p>Quản Lý Nhân Viên</p>
          </a>

          <a href="suggestsupport.php" class="nav-link">
            <i class="nav-icon fas fa-life-ring"></i>
            <p>Quản Lý Hỗ Trợ</p>
          </a>

        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">

    <section class="content-header">
      <div class="container-fluid">
        <h1>Quản lý nhân viên</h1>

        <?php if (isset($_GET['msg'])): ?>
          <div class="alert alert-<?php echo $_GET['msg'] === 'deleted' ? 'success' : 'danger'; ?>">
            <?php echo $_GET['msg'] === 'deleted'
              ? 'Đã xóa nhân viên.'
              : 'Xóa nhân viên thất bại.'; ?>
          </div>
        <?php endif; ?>

      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-body">

            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>Mã NV</th>
                  <th>Tên đăng nhập</th>
                  <th>Tên nhân viên</th>
                  <th>Email</th>
                  <th>SĐT</th>
                  <th>Vai trò</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = mysqli_fetch_array($query_getAllEmp)) { ?>
                  <tr>
                    <td><?php echo $row['maNhanVien']; ?></td>
                    <td><?php echo $row['tenDangNhap']; ?></td>
                    <td><?php echo $row['tenNhanVien']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['soDienThoai']; ?></td>
                    <td>
                      <a class="btn btn-danger"
                         href="../../function.php?deleteEmployee=<?php echo (int)$row['maNhanVien']; ?>"
                         onclick="return confirm('Xóa nhân viên #<?php echo (int)$row['maNhanVien']; ?>?');">
                        Xóa
                      </a>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </section>

  </div>

  <footer class="main-footer">
    <?php include("../footer.php"); ?>
  </footer>

</div>
</body>
</html>
