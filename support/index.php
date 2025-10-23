<?php
// C:\xampp\htdocs\shopquanao\support\index.php
require_once("../conection.php");
session_start();

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

/* Ngăn cache trang để không dùng token cũ khi bấm Back */
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// ====== Lấy danh mục để render mega menu giống trang chủ ======
$sql_getCategory = "SELECT * FROM danhmuc ORDER BY maDanhMuc DESC ";
$query_getCategory = mysqli_query($mysqli, $sql_getCategory);

$sql_getCategoryMobile = "SELECT * FROM danhmuc ORDER BY maDanhMuc DESC ";
$query_getCategoryMobile = mysqli_query($mysqli, $sql_getCategoryMobile);

// Helper escape
if (!function_exists('e')) { function e($s){ return htmlspecialchars($s ?? "", ENT_QUOTES, 'UTF-8'); } }

// Flash message
$ok  = isset($_GET['ok'])  ? intval($_GET['ok']) : 0;
$err = isset($_GET['err']) ? $_GET['err'] : "";
?>
<!doctype html>
<html class="no-js" lang="vi">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Hỗ trợ khách hàng | DirtyCoins Clone</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico">
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"
          integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

  <!-- Google Fonts -->
  <link href='https://fonts.googleapis.com/css?family=Norican' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

  <!-- CSS giống trang chủ -->
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/owl.carousel.css">
  <link rel="stylesheet" href="../css/owl.theme.css">
  <link rel="stylesheet" href="../css/owl.transitions.css">
  <link rel="stylesheet" href="../css/jquery-ui.css">
  <link rel="stylesheet" href="../css/meanmenu.min.css">
  <link rel="stylesheet" href="../lib/css/nivo-slider.css">
  <link rel="stylesheet" href="../lib/css/preview.css">
  <link rel="stylesheet" href="../css/animate.css">
  <link rel="stylesheet" href="../css/magic.css">
  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="../css/responsive.css">
  <script src="../js/vendor/modernizr-2.8.3.min.js"></script>

  <!-- Chút style nhẹ cho card form -->
  <style>
    .support-heading { text-align:center; margin: 0 0 15px; }
    .support-heading h1 { font-size: 26px; text-transform: uppercase; }
    .support-form-card { background:#fff; border:1px solid #eee; border-radius:8px; padding:24px; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
    .form-label { font-weight:600; }
    .is-invalid { border-color:#dc3545; }
    .required::after{ content:" *"; color:#e03550; }
    .helper { font-size:13px; color:#888; }
  </style>
</head>

<body>
<header>

  <!-- Top link giống trang chủ -->
  <div class="top-link">
    <div class="container">
      <div class="row">
        <div class="col-md-7 col-md-offset-3 col-sm-9 hidden-xs"></div>
        <div class="col-md-2 col-sm-3">
          <div class="dashboard">
            <div class="account-menu">
              <ul>
                <li class="search">
                  <a href="#"><i class="fa fa-search"></i></a>
                  <ul class="search">
                    <form action="../product/search.php" method="POST">
                      <li style="background-color:white;border:none">
                        <select name="search_by" style="border:none">
                          <option value="name">Tìm theo tên sản phẩm</option>
                          <option value="category">Tìm theo danh mục sản phẩm</option>
                        </select>
                      </li>
                      <li>
                        <input type="text" name="search">
                        <button type="submit"><i class="fa fa-search"></i></button>
                      </li>
                    </form>
                  </ul>
                </li>
                <li>
                  <a href="#" name="bars"><i class="fa fa-bars"></i></a>
                  <ul>
                    <?php if (isset($_SESSION['maKhachHang'])) { ?>
                      <li><a href="../user/infoCustomer.php" name="info-user">Thông tin tài khoản</a></li>
                      <li><a href="../cart/index.php">Giỏ hàng</a></li>
                      <li><a href="../function.php?logout-user=1" name="logout-user">Đăng xuất</a></li>
                    <?php } else { ?>
                      <li><a href="../user/login.php" name="login-user">Đăng nhập</a></li>
                    <?php } ?>
                  </ul>
                </li>
              </ul>
            </div>
            <div class="cart-menu">
              <ul><li><a href="../cart/index.php"></a></li></ul>
            </div>
            <div class="cart-menu"><i class="fa fa-sign-in" style="width:30px;height:30px"></i></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main menu giống trang chủ -->
  <div class="mainmenu-area home2 bg-color-tr product-items">
    <div class="container">
      <div class="row">
        <div class="col-md-3">
          <div class="logo">
            <a href="../index.php" style="margin-top: 5px;border-radius:50%">
              <img src="../image/logo.png" alt="" style="border-radius:50%">
            </a>
          </div>
        </div>
        <div class="col-md-9">
          <div class="mainmenu" style="color: black;">
            <nav>
              <ul style="padding-left:40px">
                <li><a href="../index.php">Trang Chủ</a></li>
                <li class="mega-women"><a>Sản Phẩm</a>
                  <div class="mega-menu women">
                    <div class="part-1" style="display:flex;">
                      <?php while ($row_getCategory = mysqli_fetch_array($query_getCategory)) { ?>
                        <span>
                          <a href="../product/allCategory.php?id=<?php echo $row_getCategory['maDanhMuc']; ?>">
                            <?php echo $row_getCategory['tenDanhMuc']; ?></a>
                          <?php
                            $sql_getProductCategory = "SELECT * FROM sanpham WHERE maDanhMuc='" . $row_getCategory['maDanhMuc'] . "'";
                            $query_getProductCategory = mysqli_query($mysqli, $sql_getProductCategory);
                            while ($row_getProductCategory = mysqli_fetch_array($query_getProductCategory)) {
                          ?>
                            <a href="../product/productDetail.php?id=<?php echo $row_getProductCategory['maSanPham']; ?>">
                              <?php echo $row_getProductCategory['tenSanPham']; ?></a>
                          <?php } ?>
                        </span>
                      <?php } ?>
                    </div>
                  </div>
                </li>
                <li class="mega-women"><a href="../product/categoryList.php">Danh mục</a></li>
                <!-- Giữ HỖ TRỢ, BỎ 'Liên hệ' -->
                <li class="active"><a href="./index.php">Hỗ trợ</a></li>
              </ul>
            </nav>
          </div>
        </div>

        <!-- Mobile menu -->
        <div class="col-sm-12">
          <div class="mobile-menu">
            <nav>
              <ul>
                <li><a href="../index.php">Trang Chủ</a></li>
                <li><a href="#">Danh mục</a></li>
                <li class="active"><a href="./index.php">Hỗ trợ</a></li>
                <li><a>Sản phẩm</a>
                  <ul>
                    <?php while ($row_getCategoryMobile = mysqli_fetch_array($query_getCategoryMobile)) { ?>
                      <li><a href="../product/allCategory.php?id=<?php echo $row_getCategoryMobile['maDanhMuc']; ?>">
                        <?php echo $row_getCategoryMobile['tenDanhMuc']; ?></a></li>
                    <?php } ?>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>

      </div>
    </div>
  </div>
</header>
<!-- slider area start -->
<div class="slider-area home2">
  <div class="bend niceties preview-2">
    <div id="nivoslider" class="slides">
      <img src="../image/slider/slider6.png" alt="" title="#slider-direction-1" />
      <img src="../image/slider/slider7.png" alt="" title="#slider-direction-2" />
    </div>

    <!-- direction 1 -->
    <div id="slider-direction-1" class="t-cn slider-direction">
      <div class="slider-progress"></div>
      <div class="slider-content t-lfl s-tb slider-1">
        <div class="title-container s-tb-c title-compress">
          <h1 class="title1">DirtyCoins VietNam</h1>
          <h2 class="title2">DirtyCoins x 16 Tyth</h2>
          <h3 class="title3">Chất lượng sản phẩm tạo nên danh dự</h3>
        </div>
      </div>
    </div>

    <!-- direction 2 -->
    <div id="slider-direction-2" class="slider-direction">
      <div class="slider-progress"></div>
      <div class="slider-content t-lfl s-tb slider-2">
        <div class="title-container s-tb-c">
          <h1 class="title1">DirtyCoins VietNam</h1>
          <h2 class="title2">DirtyCoins x Minh Lai</h2>
          <h3 class="title3">Chất lượng sản phẩm tạo nên danh dự</h3>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- slider area end -->


<!-- Nội dung trang hỗ trợ (không dùng slider) -->
<div class="container" style="margin-top:40px; margin-bottom:60px;">
  <div class="support-heading">
    <h1>HỖ TRỢ KHÁCH HÀNG</h1>
    <p class="helper">Điền thông tin bên dưới để Shop hỗ trợ nhanh nhất.</p>
  </div>

  <?php if($ok): ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> Gửi yêu cầu thành công! Chúng tôi sẽ phản hồi qua email của bạn sớm nhất.</div>
  <?php endif; ?>
  <?php if($err): ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <?php echo e($err); ?></div>
  <?php endif; ?>

  <div class="row">
  <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
    <div class="support-form-card">
     <form method="post" action="/shopquanao/support/submit.php" id="supportForm" novalidate>
          <div class="mb-3">
            <label class="form-label required">Tiêu đề</label>
            <input type="text" name="tieu_de" class="form-control" placeholder="Ví dụ: Cần đổi size áo" required maxlength="255">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label required">Tên khách hàng</label>
              <input type="text" name="ten_khach_hang" class="form-control" placeholder="Nguyễn Văn A" required maxlength="100">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Mã khách hàng</label>
              <input type="text" name="ma_khach_hang" class="form-control" placeholder="VD: KH0001" maxlength="50">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label required">Nội dung cần hỗ trợ</label>
            <textarea name="noi_dung_can_ho_tro" class="form-control" rows="4" placeholder="Mô tả vấn đề bạn gặp..." required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Vật phẩm cần hỗ trợ</label>
            <input type="text" name="vat_pham_can_ho_tro" class="form-control" placeholder="Ví dụ: Áo thun DirtyCoins DC001 (Đen, Size M)" maxlength="255">
          </div>
          <div class="mb-3">
            <label class="form-label required">Email của bạn (để shop liên hệ)</label>
            <input type="email" name="email" class="form-control" placeholder="email@domain.com" required maxlength="150">
          </div>
          <input type="hidden" name="csrf" value="<?php echo e($csrf); ?>">
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-dark"><i class="fa fa-paper-plane"></i> Gửi yêu cầu</button>
          </div>
        </form>
      </div>
    <p class="helper" style="margin-top:10px; text-align:center;">
      Bằng việc gửi biểu mẫu, bạn đồng ý cho phép chúng tôi liên hệ qua email để xử lý yêu cầu.
    </p>
  </div>
  </div>
</div>

<!-- Footer giống trang chủ -->
<footer>
  <div class="footer-top-area">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-4">
          <div class="footer-contact">
            <img src="#" alt="">
            <p>Trang web được xây dựng bởi Thành Đạt và Quốc Hưng, dưới đây là thông tin liên lạc: </p>
            <ul class="address">
              <li><span class="fa fa-phone"></span> 0334043054</li>
              <li><span class="fa fa-envelope-o"></span> quochungdz2017@gmail.com</li>
            </ul>
          </div>
        </div>
        <div class="col-md-3 hidden-sm">
          <div class="footer-tweets">
            <div class="footer-title"><h3>Mô tả</h3></div>
            <div class="twitter-text" style="float:left;">
              <p>Đây là trang web bán quần áo thời trang dựa trên trang web https://dirtycoins.vn/
                được tạo ra nhằm phục vụ cho môn học thực tập tốt nghiệp</p>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-4">
          <div class="footer-support">
            <div class="footer-title"><h3>Họ và tên</h3></div>
            <div class="footer-menu">
              <ul>
                <li><a href="#">Võ Thành Đạt</a></li>
                <li><a href="#">Phan Quốc Hưng</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-4">
          <div class="footer-info" style="width: 170px;">
            <div class="footer-title"><h3>Mã số sinh viên</h3></div>
            <div class="footer-menu">
              <ul>
                <li><a href="#">2251120285</a></li>
                <li><a href="#">2251120292</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- JS giống trang chủ -->
<script src="../js/vendor/jquery-1.12.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/wow.min.js"></script>
<script src="../js/jquery-price-slider.js"></script>
<script src="../lib/js/jquery.nivo.slider.js"></script>
<script src="../lib/home.js"></script>
<script src="../js/jquery.meanmenu.js"></script>
<script src="../js/owl.carousel.min.js"></script>
<script src="../js/jquery.elevatezoom.js"></script>
<script src="../js/jquery.scrollUp.min.js"></script>
<script src="../js/plugins.js"></script>
<script src="../js/main.js"></script>

<script>
// Simple client-side validation feedback
document.getElementById('supportForm').addEventListener('submit', function(e){
  var form = this;
  var ok = true;
  form.querySelectorAll('[required]').forEach(function(el){
    if(!el.value.trim()){
      el.classList.add('is-invalid');
      ok = false;
    } else {
      el.classList.remove('is-invalid');
    }
  });
  if(!ok){ e.preventDefault(); alert('Vui lòng điền đầy đủ các trường bắt buộc (*)'); }
});
</script>
</body>
</html>
