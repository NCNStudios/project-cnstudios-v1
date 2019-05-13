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

          if(isset($_REQUEST["id"]))
            {
              $query_getinfo_member = "SELECT * FROM members WHERE id_members = '".$_REQUEST['id']."'";
              $datamember = $conn->query($query_getinfo_member);

              $phong_mem = "";
              $hoten_mem = "";
              $quequan_mem = "";
              $sdt_mem = "";
              $ngaybd_mem = "";

              if ($datamember->num_rows > 0) {
                  while($row = $datamember->fetch_assoc()) {
                      $phong_mem = $row["phong"];
                      $hoten_mem = $row["ten_members"];
                      $quequan_mem = $row["que_quan"];
                      $sdt_mem = $row["sdt"];
                      $ngaybd_mem = $row["ngaybd"];
                  }
              }
              else {
                  echo 'No result';
              }

                        // Lay danh sách nhà trọ của uid
                $query_get_list_home = "SELECT * FROM homes WHERE oauth_uid = '".$userData['oauth_uid']."'";
                $home_list = mysqli_query($conn, $query_get_list_home);

                $mem_phong = "";
                $mem_hoten = "";
                $mem_quequan = "";
                $mem_sdt = "";
                $mem_idnt = "";
                $mem_tennt = "";
                $mem_ngaybdthue = "";
                $uid = "".$userData['oauth_uid'];
                $date_modified = (new \DateTime())->format('Y-m-d H:i:s');

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if(isset($_POST["phong"])) { $mem_phong = $_POST['phong'];  }
                    if(isset($_POST["hoten"])) { $mem_hoten = $_POST['hoten']; }
                    if(isset($_POST["quequan"])) { $mem_quequan = $_POST['quequan']; }
                    if(isset($_POST["sdt"])) { $mem_sdt = $_POST['sdt']; }
                    if(isset($_POST["nhatro"])) { $mem_idnt = $_POST['nhatro']; }
                    if(isset($_POST["ngaybdthue"])) { $mem_ngaybdthue = $_POST['ngaybdthue']; }

                    $sql1 = "SELECT tennhatro FROM homes where id_home = '$mem_idnt'";
                    $data = $conn->query($sql1);

                    if ($data->num_rows > 0) {
                        while($row = $data->fetch_assoc()) {
                            $tennt = $row["tennhatro"];
                        }
                    } else {
                        echo 'No result';
                    }

                    $sql2 = "UPDATE members SET ten_members='$mem_hoten',que_quan='$mem_quequan',sdt='$mem_sdt',phong='$mem_phong',ngaybd='$mem_ngaybdthue' WHERE id_members = '".$_REQUEST['id']."'"; 

                    if ($conn->query($sql2) == TRUE) {
                       header("Location:./MembersList.php");
                    } else {
                       echo "Thất bại: " . $conn->error;
                    }
                }
              }
        else
        {
          header("Location:http://localhost:8080/NienLuan2/CNStudios");
        }

      mysqli_close($conn);
    }
}
else{
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
          <li class="nav-item active">
            <a class="nav-link" href="./MembersList.php">
              <i class="material-icons">content_paste</i>
              <p>Danh sách thành viên</p>
            </a>
          </li>
          <li class="nav-item ">
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
            <a class="navbar-brand" href="#pablo">Sửa thông tin thành viên</a>
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
                  <a class="dropdown-item" href="#">Profile</a>
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
                  <h4 class="card-title">Sửa thông tin thành viên</h4>
                </div>
                <div class="card-body">
                  <form action="" method="POST" id="frm_upload">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Phòng</label>
                          <input type="text" class="form-control" name="phong" value="<?php echo $phong_mem ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Họ tên</label>
                          <input type="text" class="form-control" name="hoten" value="<?php echo $hoten_mem ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Quê quán</label>
                          <input type="text" class="form-control" name="quequan" value="<?php echo $quequan_mem ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Số điện thoại</label>
                          <input type="text" class="form-control" name="sdt" value="<?php echo $sdt_mem ?>">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Ngày bắt đầu thuê</label>
                          <input type="text" class="form-control" id="start_datepicker" name="ngaybdthue" value="<?php echo $ngaybd_mem ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Thuộc nhà trọ: </label>
                          <select class="sel_loaiphong" name="nhatro">
                            <?php 
                                while ($home_post = mysqli_fetch_array($home_list, MYSQLI_ASSOC)) {
                                    echo "<option value=\"".$home_post["tennhatro"]."\">".$home_post["tennhatro"]."</option>";
                                }
                            ?>
                                
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

</body>

</html>
