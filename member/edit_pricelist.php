<?php 
require_once '../connect/connect.php';
require_once '../lib/login/google-login/config.php';
require_once '../lib/login/google-login/User.class.php';
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
    $uid = "".$userData['oauth_uid'];

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
              $query_getinfo_price = "SELECT * FROM homes WHERE id_home = '".$_REQUEST['id']."'";
              $dataprice = $conn->query($query_getinfo_price);

              $gia_dien = "";
              $gia_nuoc = "";
              $gia_phong = "";
              $phu_thu = "";

              if ($dataprice->num_rows > 0) {
                  while($row = $dataprice->fetch_assoc()) {
                      $ten_nt = $row["tennhatro"];
                      $gia_dien = $row["gia_dien"];
                      $gia_nuoc = $row["gia_nuoc"];
                      $gia_phong = $row["gia_phong"];
                      $phu_thu = $row["phu_thu"];
                  }
              } 
              else {
                  echo 'No result';
              }

              if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if(isset($_POST["giaphong"])) { $gia_phong = $_POST['giaphong'];  }
                if(isset($_POST["giadien"])) { $gia_dien = $_POST['giadien']; }
                if(isset($_POST["gianuoc"])) { $gia_nuoc = $_POST['gianuoc']; }
                if(isset($_POST["phuthu"])) { $phu_thu = $_POST['phuthu']; }

                $sql2 = "UPDATE homes SET gia_phong='$gia_phong', gia_dien='$gia_dien', gia_nuoc='$gia_nuoc', phu_thu='$phu_thu' WHERE id_home = '".$_REQUEST['id']."'";

                if ($conn->query($sql2) == TRUE) {
                    header("Location:./pricelist.php");
                } else {
                   echo "Update thất bại: " . $conn->error;
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

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
  <link rel="stylesheet" href="../css/table.css">

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <link href="../lib/summernote/summernote-lite.css" rel="stylesheet">
  <script src="../lib/summernote/summernote-lite.js"></script>

  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

</head>

<body>
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
          <li class="nav-item active">
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
            <a class="navbar-brand" href="#pablo">Sửa bảng giá dịch vụ</a>
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
                  <h4 class="card-title">Sửa bảng giá dịch vụ</h4>
                  <p class="card-category"> </p>
                </div>
                <div class="card-body">
                  <form action="" method="POST">
                    <div class="form-group">
                        <input name="tennhatro" type="text" class="form-control" id="tennhatro" placeholder="Tên nhà trọ..." value="<?php echo $ten_nt ?>" disabled>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold" for="exampleFormControlFile1">Giá phòng (VNĐ/tháng)</label>
                          <input name="giaphong" type="number" min="0" class="form-control" id="giaphong" value="<?php echo $gia_phong ?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold" for="exampleFormControlFile1">Giá điện (VNĐ/KWh)</label>
                          <input name="giadien" type="number" min="0" class="form-control" id="giadien" value="<?php echo $gia_dien ?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold" for="exampleFormControlFile1">Giá nước (VNĐ/m<sup>3</sup>)</label>
                          <input name="gianuoc" type="number" min="0" class="form-control" id="gianuoc" value="<?php echo $gia_nuoc ?>">
                        </div>
                        <div class="form-group col-md-3">
                          <label class="font-weight-bold" for="exampleFormControlFile1">Phụ thu (wifi, giữ xe...)</label>
                          <input name="phuthu" type="number" min="0" class="form-control" id="phuthu" value="<?php echo $phu_thu ?>">
                        </div>
                    </div>
                  <button name="video-upload" type="submit" class="btn btn-primary">Cập nhật  <i class="fa-spin fas fa-sync-alt"></i></button>
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
      format: 'dd-mm-yyyy',
      showOtherMonths: true
    });
    $('#end_datepicker').datepicker({
      format: 'dd-mm-yyyy',
      showOtherMonths: true
    });
  </script>

   <script>
      $('#mota').summernote({
        placeholder: 'Mô tả...',
        tabsize: 2,
        minHeight:100,
        height: 100,
      });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img_preview')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        function loadingImage(){
                document.getElementById("img_preview").src = "../images/logo/loading.jpg";
        }
        function changeimageURL(){
            var pastedURL = document.getElementById("gd_thumbnail").value;
            if(pastedURL=="")
                document.getElementById("img_preview").src = "../images/logo/preview.png";
            else
                document.getElementById("img_preview").src = pastedURL;
        }

        function img_error(){
            document.getElementById("img_preview").src = "../images/logo/image error.jpg";
        }
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

  <script type="text/javascript" src="https://apis.google.com/js/api.js"></script>

</body>

</html>
