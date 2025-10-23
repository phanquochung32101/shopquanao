<?php
// C:\xampp\htdocs\shopquanao\admin\pages\suggestsupport.php
include("../../conection.php");
session_start();
if (!isset($_SESSION['maQuanLy'])) {
  header('location:login.php');
  exit;
}

// CSRF cho admin
if (empty($_SESSION['admin_csrf'])) {
  $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
}
$ADMIN_CSRF = $_SESSION['admin_csrf'];

$id = intval($_SESSION['maQuanLy']);
$sql_quanly = "SELECT * FROM quanly WHERE maQuanLy = $id LIMIT 1";
$query_quanly = mysqli_query($mysqli, $sql_quanly);
$row_quanly = mysqli_fetch_array($query_quanly);

// Đảm bảo có cột status (nếu DB là MySQL/MariaDB mới sẽ hỗ trợ IF NOT EXISTS)
@mysqli_query($mysqli, "ALTER TABLE support_requests ADD COLUMN IF NOT EXISTS status TINYINT(1) NOT NULL DEFAULT 0");

// Handle actions (POST)
function flash_redirect($params = []) {
  $qs = http_build_query($params);
  header("Location: suggestsupport.php" . ($qs ? "?$qs" : ""));
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Kiểm tra CSRF
  if (!isset($_POST['csrf']) || !hash_equals($_SESSION['admin_csrf'], $_POST['csrf'])) {
    flash_redirect(['err' => 'Phiên không hợp lệ. Vui lòng thử lại.']);
  }

  $action = $_POST['action'] ?? '';
  $rid    = isset($_POST['id']) ? intval($_POST['id']) : 0;

  if ($rid <= 0) {
    flash_redirect(['err' => 'Thiếu ID yêu cầu hợp lệ.']);
  }

  if ($action === 'resolve') {
    $stmt = mysqli_prepare($mysqli, "UPDATE support_requests SET status=1 WHERE id=?");
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "i", $rid);
      $ok = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      $ok ? flash_redirect(['ok' => 'Đã đánh dấu đã xử lý.']) : flash_redirect(['err' => 'Cập nhật thất bại.']);
    } else {
      flash_redirect(['err' => 'Không thể chuẩn bị câu lệnh.']);
    }
  } elseif ($action === 'delete') {
    $stmt = mysqli_prepare($mysqli, "DELETE FROM support_requests WHERE id=?");
    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "i", $rid);
      $ok = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      $ok ? flash_redirect(['ok' => 'Đã xóa yêu cầu.']) : flash_redirect(['err' => 'Xóa thất bại.']);
    } else {
      flash_redirect(['err' => 'Không thể chuẩn bị câu lệnh.']);
    }
  } else {
    flash_redirect(['err' => 'Hành động không hợp lệ.']);
  }
}

// Lấy tất cả yêu cầu hỗ trợ, mới nhất trước
$sql_getAllSupport = "SELECT id, tieuDe, tenKhachHang, maKhachHang, noiDung, vatPham, email, status, created_at
                      FROM support_requests
                      ORDER BY created_at DESC, id DESC";
$query_getAllSupport = mysqli_query($mysqli, $sql_getAllSupport);

// Flash
$okMsg  = isset($_GET['ok'])  ? $_GET['ok']  : '';
$errMsg = isset($_GET['err']) ? $_GET['err'] : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Support Requests</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <style>
    .badge-status{font-size:90%}
    .nowrap-ellipsis{max-width:260px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <?php include("navbar.php"); ?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../index.php" class="brand-link">
      <img src="../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity:.8">
      <span class="brand-text font-weight-light" style="font-size:17px;">
        <?php echo htmlspecialchars($row_quanly['tenQuanLy'] ?? "", ENT_QUOTES, 'UTF-8'); ?>
      </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <a href="../index.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i><p>Thống Kê</p>
          </a>
          <a href="product.php" class="nav-link">
            <i class="nav-icon fas fa-th"></i><p>Quản Lý Sản Phẩm</p>
          </a>
          <a href="category.php" class="nav-link">
            <i class="nav-icon fas fa-table"></i><p>Quản Lý Danh Mục</p>
          </a>
          <a href="bills.php" class="nav-link">
            <i class="nav-icon fas fa-book"></i><p>Quản Lý Hóa Đơn</p>
          </a>
          <a href="users.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i><p>Quản Lý Khách Hàng</p>
          </a>
          <a href="suggestsupport.php" class="nav-link active">
            <i class="nav-icon fas fa-life-ring"></i><p>Quản Lý Hỗ Trợ</p>
          </a>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Header -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1>Yêu Cầu Hỗ Trợ</h1></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
              <li class="breadcrumb-item active">Support Requests</li>
            </ol>
          </div>
        </div>
        <?php if($okMsg): ?>
          <div class="alert alert-success mt-2"><?php echo htmlspecialchars($okMsg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <?php if($errMsg): ?>
          <div class="alert alert-danger mt-2"><?php echo htmlspecialchars($errMsg, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row"><div class="col-12">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Danh sách yêu cầu hỗ trợ từ khách hàng</h3></div>
            <div class="card-body">
              <table id="supportTable" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Mã yêu cầu</th>
                    <th>Tiêu đề</th>
                    <th>Khách hàng</th>
                    <th>Email</th>
                    <th>Vật phẩm</th>
                    <th>Trạng thái</th>
                    <th>Thời gian</th>
                    <th>Hành động</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 0;
                  while ($row = mysqli_fetch_assoc($query_getAllSupport)) {
                    $i++;
                    $idReq      = (int)$row['id'];
                    $tieuDe     = htmlspecialchars($row['tieuDe'], ENT_QUOTES, 'UTF-8');
                    $tenKH      = htmlspecialchars($row['tenKhachHang'], ENT_QUOTES, 'UTF-8');
                    $maKH       = htmlspecialchars($row['maKhachHang'] ?? '', ENT_QUOTES, 'UTF-8');
                    $noiDung    = htmlspecialchars($row['noiDung'], ENT_QUOTES, 'UTF-8');
                    $vatPham    = htmlspecialchars($row['vatPham'] ?? '', ENT_QUOTES, 'UTF-8');
                    $email      = htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8');
                    $status     = (int)($row['status'] ?? 0);
                    $created_at = htmlspecialchars($row['created_at'], ENT_QUOTES, 'UTF-8');
                    $khDisplay  = $tenKH . ($maKH !== '' ? " (".$maKH.")" : "");
                    $badge = $status === 1
                      ? '<span class="badge badge-success badge-status">Đã xử lý</span>'
                      : '<span class="badge badge-warning badge-status">Chờ xử lý</span>';
                    ?>
                    <tr>
                      <td><?php echo $i; ?></td>
                      <td><?php echo $idReq; ?></td>
                      <td class="nowrap-ellipsis" title="<?php echo $tieuDe; ?>"><?php echo $tieuDe; ?></td>
                      <td><?php echo $khDisplay; ?></td>
                      <td><?php echo $email; ?></td>
                      <td class="nowrap-ellipsis" title="<?php echo $vatPham; ?>"><?php echo $vatPham; ?></td>
                      <td><?php echo $badge; ?></td>
                      <td><?php echo $created_at; ?></td>
                      <td>
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#viewModal<?php echo $idReq; ?>">
                          <i class="fas fa-eye"></i>
                        </button>
                        <?php if($status === 0): ?>
                          <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#resolveModal<?php echo $idReq; ?>">
                            <i class="fas fa-check"></i>
                          </button>
                        <?php endif; ?>
                        <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $idReq; ?>">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>

                    <!-- Modal xem chi tiết -->
                    <div class="modal fade" id="viewModal<?php echo $idReq; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg"><div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Chi tiết yêu cầu #<?php echo $idReq; ?></h5>
                          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                          <dl class="row">
                            <dt class="col-sm-3">Tiêu đề</dt>
                            <dd class="col-sm-9"><?php echo $tieuDe; ?></dd>

                            <dt class="col-sm-3">Khách hàng</dt>
                            <dd class="col-sm-9"><?php echo $khDisplay; ?></dd>

                            <dt class="col-sm-3">Email</dt>
                            <dd class="col-sm-9"><?php echo $email; ?></dd>

                            <dt class="col-sm-3">Vật phẩm</dt>
                            <dd class="col-sm-9"><?php echo ($vatPham !== '' ? $vatPham : '<i>(không có)</i>'); ?></dd>

                            <dt class="col-sm-3">Nội dung</dt>
                            <dd class="col-sm-9" style="white-space:pre-wrap"><?php echo $noiDung; ?></dd>

                            <dt class="col-sm-3">Trạng thái</dt>
                            <dd class="col-sm-9"><?php echo $badge; ?></dd>

                            <dt class="col-sm-3">Thời gian</dt>
                            <dd class="col-sm-9"><?php echo $created_at; ?></dd>
                          </dl>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                      </div></div>
                    </div>

                    <!-- Modal xác nhận ĐÃ XỬ LÝ -->
                    <div class="modal fade" id="resolveModal<?php echo $idReq; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog"><div class="modal-content">
                        <form method="post" action="suggestsupport.php">
                          <div class="modal-header">
                            <h5 class="modal-title">Đánh dấu đã xử lý</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                          </div>
                          <div class="modal-body">
                            Xác nhận đánh dấu yêu cầu <strong>#<?php echo $idReq; ?></strong> là <strong>ĐÃ XỬ LÝ</strong>?
                          </div>
                          <div class="modal-footer">
                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($ADMIN_CSRF, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="resolve">
                            <input type="hidden" name="id" value="<?php echo $idReq; ?>">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-success">Xác nhận</button>
                          </div>
                        </form>
                      </div></div>
                    </div>

                    <!-- Modal xác nhận XOÁ -->
                    <div class="modal fade" id="deleteModal<?php echo $idReq; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog"><div class="modal-content">
                        <form method="post" action="suggestsupport.php">
                          <div class="modal-header">
                            <h5 class="modal-title">Xóa yêu cầu</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                          </div>
                          <div class="modal-body">
                            Bạn chắc chắn muốn <strong>xóa</strong> yêu cầu <strong>#<?php echo $idReq; ?></strong>? Hành động không thể hoàn tác.
                          </div>
                          <div class="modal-footer">
                            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($ADMIN_CSRF, ENT_QUOTES, 'UTF-8'); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $idReq; ?>">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">Xóa</button>
                          </div>
                        </form>
                      </div></div>
                    </div>
                    <?php
                  } // while
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div></div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <?php include("../footer.php") ?>
  </footer>

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- Scripts -->
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>

<script>
  $(function () {
    $('#supportTable').DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "order": [[7, "desc"]], // sort theo Thời gian
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json"
      },
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#supportTable_wrapper .col-md-6:eq(0)');
  });
</script>
</body>
</html>
