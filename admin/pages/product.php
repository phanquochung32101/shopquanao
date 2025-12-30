<?php
include("../../conection.php");
session_start();
// Cho phép admin hoặc nhân viên
if (!isset($_SESSION['maQuanLy']) && !isset($_SESSION['maNhanVien'])) {
  header('location:../login.php');
  exit;
}

if (isset($_SESSION['maQuanLy'])) {
  $id = (int)$_SESSION['maQuanLy'];
  $sql_quanly = "SELECT * FROM quanly WHERE maQuanLy = $id LIMIT 1";
  $query_quanly = mysqli_query($mysqli, $sql_quanly);
  $row_quanly = mysqli_fetch_array($query_quanly);
} else {
  $id = (int)$_SESSION['maNhanVien'];
  $sql_nv = "SELECT * FROM nhanvien WHERE maNhanVien = $id LIMIT 1";
  $query_nv = mysqli_query($mysqli, $sql_nv);
  $row_nv = mysqli_fetch_array($query_nv);
  $row_quanly = array();
  $row_quanly['tenQuanLy'] = isset($row_nv['tenNhanVien']) ? $row_nv['tenNhanVien'] : 'Nhân viên';
}

$sqlAllProduct = "SELECT * FROM sanpham ORDER BY maSanPham DESC";
$query_AllProduct = mysqli_query($mysqli, $sqlAllProduct);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include("navbar.php"); ?>
    <!-- /.navbar -->

    <?php include("../menu.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Quản Lý Sản Phẩm</h1>
            </div>

            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="../index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active">Quản lý sản phẩm</li>
              </ol>
            </div>
          </div>
          <a type="button" class="btn btn-block btn-success" style="float:right;width: 150px; text-align: center;"
            href="addProduct.php">Thêm sản phẩm</a>

        </div>

      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>STT</th>
                        <th>Mã Sản Phẩm</th>
                        <th>Danh mục</th>
                        <th>Tên sản phẩm</th>
                        <th>Mô tả</th>
                        <th>Số lượng</th>
                        <th>Trạng thái sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th>Tùy chỉnh</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $i = 0;
                      while ($rowAllProduct = mysqli_fetch_array($query_AllProduct)) {
                        $i++;
                        // get name category
                        $sql_nameCategory = "SELECT tenDanhMuc,maDanhMuc FROM danhmuc WHERE maDanhMuc= '" . $rowAllProduct['maDanhMuc'] . "' LIMIT 1";
                        $query_nameCategory = mysqli_query($mysqli, $sql_nameCategory);
                        $row_getNameCategory = mysqli_fetch_array($query_nameCategory);

                        //get name level product
                        $sql_nameLevel = "SELECT tenTrangThai,maTrangThai FROM trangthaisanpham WHERE maTrangThai= '" . $rowAllProduct['trangThaiSanPham'] . "' LIMIT 1";
                        $query_nameLevel = mysqli_query($mysqli, $sql_nameLevel);
                        $row_getNameLevel = mysqli_fetch_array($query_nameLevel);

                      ?>
                        <tr>
                          <td>
                            <?php echo $i ?>
                          </td>
                          <td>
                            <?php echo $rowAllProduct['maSanPham'] ?>
                          </td>
                          <td>
                            <?php echo $row_getNameCategory['tenDanhMuc'] ?>
                          </td>
                          <td>
                            <?php echo $rowAllProduct['tenSanPham'] ?>
                          </td>
                          <td>
                            <?php echo $rowAllProduct['moTa'] ?>
                          </td>
                          <td>
                            <?php echo $rowAllProduct['soLuong'] ?>
                          </td>
                          <td>
                            <?php echo $row_getNameLevel['tenTrangThai'] ?>
                          </td>
                          <td>
                            <?php echo $rowAllProduct['hinhAnh'] ?>
                          </td>
                          <td>
                            <a type="button" class="btn btn-primary" style="margin-bottom: 10px;"
                              href="actionProduct.php?id=<?php echo $rowAllProduct['maSanPham'] ?>">
                              Sửa sản phẩm
                            </a>
                            <a type="button" class="btn btn-primary"
                              style="margin-bottom: 10px;background-color: #DB0D0D; border-color: #DB0D0D;"
                              href="../../function.php?idDelete=<?php echo $rowAllProduct['maSanPham'] ?>">
                              Xóa sản phẩm
                            </a>

                          </td>
                        </tr>
                      <?php
                      }
                      ?>

                    <tfoot>
                    </tfoot>
                  </table>
                </div>
                <!-- /.card-body -->
                <!-- Modal Delete Product-->
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content" style="text-align:center;width:600px">
                      </br>
                      </br>
                      <div class="modal-body">
                        <h5>Bạn có chắc muốn xóa sản phẩm này chứ ?</5>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" style="background:#d3f3f5 "><a
                            href="actionProduct.php?id_product=<?= $data['id'] ?>&size=<?php echo $data['size'] ?>">Xóa</a></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"
                          style="background:#f3b6b6">Không</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- END Modal Delete Product-->
                <tfoot>
                </tfoot>
                </table>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <?php include("../footer.php") ?>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->


  <!-- jquery-ui CSS
      ============================================ -->
  <link rel="stylesheet" href="../../css/jquery-ui.css">
  <!-- meanmenu CSS
      ============================================ -->
  <link rel="stylesheet" href="../../css/meanmenu.min.css">
  <!-- nivoslider CSS
      ============================================ -->
  <link rel="stylesheet" href="../../lib/css/nivo-slider.css">
  <link rel="stylesheet" href="../../lib/css/preview.css">
  <!-- animate CSS
      ============================================ -->
  <link rel="stylesheet" href="../../css/animate.css">
  <!-- magic CSS
      ============================================ -->
  <link rel="stylesheet" href="../../css/magic.css">
  <!-- normalize CSS
      ============================================ -->
  <link rel="stylesheet" href="../../css/normalize.css">
  <!-- main CSS
      ============================================ -->
  <link rel="stylesheet" href="../../css/main.css">
  <!-- style CSS
      ============================================ -->
  <link rel="stylesheet" href="../../style.css">
  <!-- responsive CSS
      ============================================ -->
  <link rel="stylesheet" href="../../css/responsive.css">
  <!-- modernizr JS
      ============================================ -->
  <script src="../../js/vendor/modernizr-2.8.3.min.js"></script>
</body>

</html>