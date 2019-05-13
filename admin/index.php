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

    if($rank != "Admin"){
      header("Location:http://localhost:8080/NienLuan2/CNStudios");
    }
    else{

      $numofhomes = 0;
      $get_homes_count = "SELECT * FROM homes";
      $home_count = mysqli_query($conn, $get_homes_count);
      while (mysqli_fetch_array($home_count, MYSQLI_ASSOC)) {
        $numofhomes++;
      }
      $numofmembers = 0;
      $get_mem_count = "SELECT * FROM users WHERE rank = 'Member'";
      $mem_count = mysqli_query($conn, $get_mem_count);
      while (mysqli_fetch_array($mem_count, MYSQLI_ASSOC)) {
        $numofmembers++;
      }

      $query_get_list_home = "SELECT * FROM homes";
      $home_list = mysqli_query($conn, $query_get_list_home);

      $query_get_list_members = "SELECT * FROM users WHERE rank = 'Member'";
      $members_list = mysqli_query($conn, $query_get_list_members);

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
    Nhà trọ sinh viên - CNStudios.tk
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="assets/demo/demo.css" rel="stylesheet" />
  <link href="../css/table.css" rel="stylesheet" />


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
          <li class="nav-item active">
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
          <li class="nav-item ">
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
            <a class="navbar-brand" href="#pablo">Dashboard</a>
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
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="fas fa-home"></i>
                  </div>
                  <p class="card-category">Số nhà trọ</p>
                  <h3 class="card-title"><?php echo $numofhomes; ?>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <a href="./home.php"> <i class="fas fa-list-ol"></i> Xem danh sách</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="fas fa-users"></i>
                  </div>
                  <p class="card-category">Số thành viên</p>
                  <h3 class="card-title"><?php echo $numofmembers; ?>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <a href="./MembersList.php"> <i class="fas fa-list-ol"></i> Xem danh sách</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="fas fa-heart"></i>
                  </div>
                  <p class="card-category">Followers</p>
                  <h3 class="card-title">Soon...
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <a><i class="fas fa-users"></i> Người theo dõi bạn</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="fas fa-signature"></i>
                  </div>
                  <p class="card-category">Đợi xác nhận</p>
                  <h3 class="card-title">Soon...
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <a href="#"> <i class="fas fa-clock"></i> Xác nhận</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <?php 
                  while ($home_post = mysqli_fetch_array($home_list, MYSQLI_ASSOC)) {
                    echo "<div class=\"col-md-4\">\n";
                    echo "<div class=\"card card-chart\">\n";
                    echo "<div class=\"card-header card-header-success\">\n";
                    echo "<a href=\"homestatus.php?id=".$home_post["id_home"]."\"><img src=\"".$home_post["anh_bia"]."\" style=\"width: 100%; height: 195px\" ></a>\n";
                    echo "</div>\n";
                    echo "<div class=\"card-body\">\n";
                    echo "<a href=\"homestatus.php?id=".$home_post["id_home"]."\"><h4 class=\"card-title\">".$home_post["tennhatro"]."</h4></a>\n";
                    echo "<p class=\"card-category\">\n";
                    echo "<span class=\"text-success\"><i class=\"fas fa-map-marker\"></i> Vị trí: </span>\n";
                    echo "".$home_post["vi_tri"]."</p>\n";
                    echo "<span class=\"text-danger\"><i class=\"fas fa-shield-alt\"></i> Status: </span>\n";
                    echo "<b>".$home_post["status"]."</b></p>\n";
                    echo "</div>\n";
                    echo "<div class=\"card-footer\">\n";
                    echo "<div class=\"stats\">\n";
                    echo "<i class=\"material-icons\">access_time</i> ".$home_post["date_modified"]."\n";
                    echo "</div>\n";
                    
                    echo "</div>\n";
                    echo "</div>\n";
                    echo "</div>";
                  }
              ?>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title">Danh sách chủ trọ</h4>
                  <a href="./members.php"><p class="card-category">Xem tất cả...</p></a>
                </div>
                <div class="card-body table-responsive">
                  <table class="table table-hover " >
                   <thead>
                      <tr>
                        <th class="th-sm">Họ và tên
                        </th>
                        <th class="th-sm">Quê quán
                        </th>
                        <th class="th-sm">SĐT
                        </th>
                        <th class="th-sm">Email
                        </th>
                        <th class="th-sm">Ngày đăng ký
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        while ($members_post = mysqli_fetch_array($members_list, MYSQLI_ASSOC)) {
                          echo "<tr>\n";
                          echo "<td>".$members_post["first_name"]." ".$members_post["last_name"]."</td>\n";
                          echo "<td>".$members_post["diachi_user"]."</td>\n";
                          echo "<td>".$members_post["sdt_user"]."</td>\n";
                          echo "<td>".$members_post["email"]."</td>\n";
                          echo "<td>".$members_post["created"]."</td>\n";
                          echo "</tr>";
                        }
                    ?>
                    </tbody>
                  </table>
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
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
  </script>
</body>

</html>
