<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <?php
  // Build admin base URL so links work whether menu is included from admin root or admin/pages/*
  $scriptDir = dirname(str_replace('\\', '/', $_SERVER['SCRIPT_NAME']));
  if (basename($scriptDir) === 'pages') {
    $adminBase = dirname($scriptDir); // .../admin
  } else {
    $adminBase = $scriptDir; // .../admin
  }
  ?>
  <a href="<?php echo $adminBase ?>/index.php" class="brand-link">
    <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light" style="font-size:17px;">
      <?php
      // Nếu là nhân viên, ưu tiên hiện tên nhân viên; ngược lại hiện tên quản lý
      if (isset($_SESSION['maNhanVien']) && !empty($_SESSION['tenNhanVien'])) {
        echo htmlspecialchars($_SESSION['tenNhanVien'], ENT_QUOTES, 'UTF-8');
      } else {
        echo htmlspecialchars($row_quanly['tenQuanLy'] ?? '', ENT_QUOTES, 'UTF-8');
      }
      ?>
    </span>
  </a>
  <!-- Sidebar -->
  <div class="sidebar">


    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
        // Nếu role nhân viên thì chỉ hiện 2 mục: Quản Lý Sản Phẩm và Quản Lý Danh Mục
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee') {
        ?>
          <a href="<?php echo $adminBase ?>/pages/product.php" class="nav-link<?php echo (basename($_SERVER['PHP_SELF']) == 'product.php') ? ' active' : '' ?>">
            <i class="nav-icon fas fa-th"></i>
            <p>Quản Lý Sản Phẩm</p>
          </a>
          <a href="<?php echo $adminBase ?>/pages/category.php" class="nav-link<?php echo (basename($_SERVER['PHP_SELF']) == 'category.php') ? ' active' : '' ?>">
            <i class="nav-icon fas fa-table"></i>
            <p>Quản Lý Danh Mục</p>
          </a>
        <?php
        } else {
          // Mặc định (admin) hiện toàn bộ menu
        ?>
          <a href="<?php echo $adminBase ?>/index.php" class="nav-link<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? ' active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Thống Kê</p>
          </a>
          <a href="<?php echo $adminBase ?>/pages/product.php" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>Quản Lý Sản Phẩm</p>
          </a>
          <a href="<?php echo $adminBase ?>/pages/category.php" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>Quản Lý Danh Mục</p>
          </a>
          <a href="<?php echo $adminBase ?>/pages/bills.php" class="nav-link">
            <i class="nav-icon fas fa-book"></i>
            <p>Quản Lý Hóa Đơn</p>
          </a>

          <a href="<?php echo $adminBase ?>/pages/users.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Quản Lý Khách Hàng</p>
          </a>
          <a href="<?php echo $adminBase ?>/pages/employee.php" 
           class="nav-link<?php echo (basename($_SERVER['PHP_SELF']) == 'employee.php') ? ' active' : '' ?>">
         <i class="nav-icon fas fa-user-tie"></i>
         <p>Quản Lý Nhân Viên</p>
          </a>
          <a href="<?php echo $adminBase ?>/pages/suggestsupport.php" class="nav-link">
            <i class="nav-icon fas fa-life-ring"></i>
            <p>Quản Lý Hỗ Trợ</p>
          </a>
        <?php } // end role check 
        ?>

    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>