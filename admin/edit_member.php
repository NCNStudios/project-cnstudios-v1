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

    if($rank != "Admin"){
      header("Location:http://localhost:8080/NienLuan2/CNStudios");
    }
    else{

          if(isset($_REQUEST["id"]))
            {
              $query_getinfo = "SELECT * FROM users WHERE id = '".$_REQUEST['id']."'";
              $datauser = $conn->query($query_getinfo);


              if ($datauser->num_rows > 0) {
                  while($row = $datauser->fetch_assoc()) {
                      $oauth_uid = $row["oauth_uid"];
                      $first_name = $row["first_name"];
                      $last_name = $row["last_name"];
                      $sdt_user = $row["sdt_user"];
                      $diachi_user = $row["diachi_user"];
                      $created = $row["created"];
                  }
              }
              else {
                  echo 'No result';
              }

                $rank = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if(isset($_POST["rank"])) { $rank = $_POST['rank']; }

                    $sql2 = "UPDATE users SET rank='$rank' WHERE id = '".$_REQUEST['id']."'"; 

                    if ($conn->query($sql2) == TRUE) {
                       header("Location:./members.php");
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
          CNStudios Admin
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
          <li class="nav-item">
            <a class="nav-link" href="./home.php">
              <i class="material-icons">home</i>
              <p>Danh sách nhà trọ</p>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./members.php">
              <i class="material-icons">content_paste</i>
              <p>Danh sách chủ trọ</p>
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
            <a class="navbar-brand" href="#pablo">Xác minh thông tin thành viên</a>
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
                  <h4 class="card-title">Xác minh thông tin thành viên</h4>
                </div>
                <div class="card-body">
                  <form action="" method="POST" id="frm_upload">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Google UID</label>
                          <input type="text" class="form-control" name="uid" value="<?php echo $oauth_uid ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Họ tên</label>
                          <input type="text" class="form-control" name="hoten" value="<?php echo $first_name ?> <?php echo $last_name ?>" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Quê quán</label>
                          <input type="text" class="form-control" name="quequan" value="<?php echo $diachi_user ?>" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Số điện thoại</label>
                          <input type="text" class="form-control" name="sdt" value="<?php echo $sdt_user ?>" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Ngày đăng ký</label>
                          <input type="text" class="form-control"  name="ngaydk" value="<?php echo $created ?>" readonly>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Đặt người dùng này là: </label>
                          <select class="sel_loaiphong" name="rank">
                            <option value="Member">Member (Chủ nhà trọ)</option>
                            <option value="User">User (Khách)</option>  
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
