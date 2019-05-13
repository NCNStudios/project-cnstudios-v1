<?php 
require_once '../connect/connect.php';
require_once '../lib/login/google-login/config.php';
require_once '../lib/login/google-login/User.class.php';
date_default_timezone_set("Asia/Ho_Chi_Minh");
mysqli_set_charset($conn, 'UTF8');

if(isset($_GET['code'])){
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if(isset($_SESSION['token'])){
    $gClient->setAccessToken($_SESSION['token']);
}

if($gClient->getAccessToken()){
    $userData = $_SESSION['userData'];

    $sql = "SELECT rank FROM users where oauth_uid = '".$userData['oauth_uid']."'";

    $data = $conn->query($sql);

    if ($data->num_rows > 0) {
        while($row = $data->fetch_assoc()) {
            $rank = $row["rank"];
        }
    } else {
        echo 'No result';
    }

    if($rank != "Member"){
      header("Location:http://localhost:8080/NienLuan2/CNStudios");
    }
    else{
      // Lấy id từ url
      if(isset($_REQUEST["id"]))
        {
              //Lấy thông tin bill
              $query_getinfo_bill = "SELECT * FROM bills WHERE id_bill = '".$_REQUEST['id']."'";
              $databill = $conn->query($query_getinfo_bill);

              if ($databill->num_rows > 0) {
                  while($row = $databill->fetch_assoc()) {
                      $hoten = $row["ten_members"];
                      $sodien = $row["so_dien"];
                      $sonuoc = $row["so_nuoc"];
                      $ghichu = $row["ghi_chu"];
                      $ngaybd = $row["ngay_bd"];
                      $ngaykt = $row["ngay_kt"];
                      $tongtien = $row["tong_tien"];
                      $idhome = $row["id_home"];
                      $thanh_toan = $row["thanh_toan"];
                  }
              }


              else {
                  echo 'No result';
              }

              //Lấy thông tin mem
              $query_getinfo_mem = "SELECT * FROM members WHERE id_home = '$idhome'";
              $datamem = $conn->query($query_getinfo_mem);

              if ($datamem->num_rows > 0) {
                  while($mem = $datamem->fetch_assoc()) {
                      $phong = $mem["phong"];
                  }
              }
              else {
                  echo 'No result';
              }

              //Lấy thông tin nhà trọ
              $query_getinfo_home = "SELECT * FROM homes WHERE id_home = '$idhome'";
              $datahome = $conn->query($query_getinfo_home);

              if ($datahome->num_rows > 0) {
                  while($home = $datahome->fetch_assoc()) {
                      $tennhatro = $home["tennhatro"];
                      $giaphong = $home["gia_phong"];
                      $giadien = $home["gia_dien"];
                      $gianuoc = $home["gia_nuoc"];
                      $phuthu = $home["phu_thu"];
                  }
              }
              else {
                  echo 'No result';
              }

              //Lấy thông tin từ form
              $ngay_bd = "";
              $ngay_kt = "";
              $so_dien = "";
              $so_nuoc = "";
              $tong_tien = "";
              $ghi_chu = "";
              $thanhtoan = "";
              $date_modified = (new \DateTime())->format('Y-m-d H:i:s');

              $uid = "".$userData['oauth_uid'];

              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST["ngayBD"])) { $ngay_bd = $_POST['ngayBD'];  }
                if(isset($_POST["ngayKT"])) { $ngay_kt = $_POST['ngayKT']; }
                if(isset($_POST["soDien"])) { $so_dien = $_POST['soDien']; }
                if(isset($_POST["soNuoc"])) { $so_nuoc = $_POST['soNuoc']; }
                if(isset($_POST["tongTien"])) { $tong_tien = $_POST['tongTien']; }
                if(isset($_POST["ghichu"])) { $ghi_chu = $_POST['ghichu']; }
                if(isset($_POST["thanhtoan"])) { $thanhtoan = $_POST['thanhtoan']; }


                $sql2 = "UPDATE bills SET ngay_bd='$ngay_bd',ngay_kt='$ngay_kt',so_dien='$so_dien',so_nuoc='$so_nuoc',ghi_chu='$ghi_chu',tong_tien='$tong_tien',thanh_toan='$thanhtoan'  WHERE id_bill = '".$_REQUEST['id']."'"; 

                  //if8
                  if ($conn->query($sql2) == TRUE) {
                     header("Location:./bills.php");
                  } 
                  else {
                     echo "1: " . $conn->error;
                  }
                }
              else{
                echo "2: " . $conn->error;
              }

        }
        else {
          echo "3: " . $conn->error;
        }

    }
}
else
{
  header("Location:http://localhost:8080/NienLuan2/CNStudios");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../images/home_marker.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    CNStudios - User Dashboard
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />

  <link href="assets/demo/demo.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/table.css">

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="assets/img/sidebar-1.jpg">
      <div class="logo">
        <a href="http://CNStudios.tk" class="simple-text logo-normal">
          CNStudios
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="./">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./user.php">
              <i class="material-icons">person</i>
              <p>Trang cá nhân</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./pricelist.php">
              <i class="far fa-list-alt"></i>
              <p>Bảng giá dịch vụ</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./home.php">
              <i class="material-icons">home</i>
              <p>Danh sách nhà trọ</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./MembersList.php">
              <i class="material-icons">content_paste</i>
              <p>Danh sách thành viên</p>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./Bills.php">
              <i class="material-icons">library_books</i>
              <p>Hóa đơn</p>
            </a>
          </li>
          <li class="nav-item active-pro ">
            <a class="nav-link" href="../contact.php">
              <i class="fas fa-headset"></i>
              <p>Liên hệ hỗ trợ</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="#pablo">Sửa thông tin hóa đơn</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="./user.php">Profile</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="../lib/logout">Log out</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Sửa thông tin hóa đơn</h4>
                  <p class="card-category">Phòng <?php echo $phong?> <i class="fas fa-home"></i> <?php echo $tennhatro ?></p>
                </div>
                <div class="card-body">
                  <form action="" method="POST">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Họ tên</label>
                          <input type="text" class="form-control" value="<?php echo $hoten ?>" disabled>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Phòng</label>
                          <input type="text" class="form-control" value="<?php echo $phong ?>" disabled>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label class="bmd-label-floating">Thuộc nhà trọ</label>
                          <input type="text" class="form-control" value="<?php echo $tennhatro ?>" disabled>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Ngày bắt đầu</label>
                          <input name="ngayBD" type="text" class="form-control" id="start_datepicker" value="<?php echo $ngaybd ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Ngày kết thúc</label>
                          <input name="ngayKT" type="text" id="end_datepicker" class="form-control" value="<?php echo $ngaykt ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Số điện</label>
                          <input name="soDien" id="soDien" type="number" min="0" class="form-control" value="<?php echo $sodien ?>" >
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Giá điện (Đồng/KWh)</label>
                          <input type="number" id="giaDien" min="0" class="form-control" disabled value="<?php echo $giadien ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Số nước</label>
                          <input name="soNuoc" id="soNuoc" type="number" min="0" class="form-control" value="<?php echo $sonuoc ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Giá nước (Đồng/m<sup>3</sup>)</label>
                          <input type="number" id="giaNuoc" min="0" class="form-control" disabled value="<?php echo $gianuoc ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Tiền phòng</label>
                          <input  type="number" id="tienPhong" min="0" class="form-control" disabled value="<?php echo $giaphong ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="bmd-label-floating">Phụ thu</label>
                          <input type="number" id="phuThu" min="0" class="form-control" disabled value="<?php echo $phuthu ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bmd-label-floating">Tổng tiền <small class="text-danger">(tự động tính)</small></label>
                          <input name="tongTien" id="tongTien" type="text" class="form-control" id="start_datepicker" readonly value="<?php echo $tongtien ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="form-group">
                            <label class="bmd-label-floating"> Thêm ghi chú gửi đến người thuê nhà trọ!</label>
                            <textarea class="form-control" rows="5" name="ghichu"><?php echo $ghichu ?></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Tình trạng hóa đơn này: </label>
                          <select class="sel_loaiphong" name="thanhtoan">
                            <option value="Chưa" <?php if ($thanh_toan == "Chưa" ) echo 'selected' ; ?>>Chưa thanh toán</option>  
                            <option value="Xong" <?php if ($thanh_toan == "Xong" ) echo 'selected' ; ?>>Đã thanh toán</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <button name="video-upload" type="submit" class="btn btn-primary">Cập nhật  <i class="fa-spin fas fa-sync-alt"></i></button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="http://CNStudios.tk">
                  CNStudios
                </a>
              </li>
              <li>
                <a href="http://CNStudios.tk">
                  About Us
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script> by
            <a href="http://CNStudios.tk" target="_blank">CNStudios.tk</a>
          </div>
        </div>
      </footer>
    </div>
  </div>
<script>
    $('#start_datepicker').datepicker({
      format: 'yyyy-mm-dd',
      showOtherMonths: true
    });
    $('#end_datepicker').datepicker({
      format: 'yyyy-mm-dd',
      showOtherMonths: true
    });
  </script>
  
  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

  <script src="assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="assets/js/plugins/arrive.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="assets/demo/demo.js"></script>

  <script>
    $('#soNuoc').keyup(function(){
        var sodien;
        var sonuoc;
        var giadien;
        var gianuoc;
        var giaphong;
        var phuthu;

        sodien = parseFloat($('#soDien').val());
        sonuoc = parseFloat($('#soNuoc').val());
        giadien = parseFloat($('#giaDien').val());
        gianuoc = parseFloat($('#giaNuoc').val());
        giaphong = parseFloat($('#tienPhong').val());
        phuthu = parseFloat($('#phuThu').val());

        var result = sodien*giadien + sonuoc*gianuoc + giaphong + phuthu;
        $('#tongTien').val(result.toFixed(0));


    });
  </script>

</body>

</html>
