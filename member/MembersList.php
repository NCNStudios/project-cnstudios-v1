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

      $query_get_list_members = "SELECT * FROM members WHERE oauth_uid = '".$userData['oauth_uid']."'";
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
    CNStudios - User Dashboard
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
  <link rel="stylesheet" href="../css/table.css">

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
            <a class="navbar-brand" href="#pablo">Danh sách người trọ</a>
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
        <!-- Table -->
            <div class="container table-responsive">
                <div class="header_wrap">
                    <div class="num_rows" style="display: flex;">
                        <h6 style="margin-top: 1.7em;margin-right: 2em;">Hiển thị </h6>
                            <div class="form-group">  
                                <select class  ="form-control" name="state" id="maxRows">
                                        <option value="10">10 Người</option>
                                        <option value="20">20 Người</option>
                                        <option value="50">50 Người</option>
                                        <option value="100">100 Người</option>
                                        <option value="5000">Tất cả</option>
                                </select>               
                            </div>
                    </div>
                    <div class="tb_search">
                        <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
                    </div>
                </div>
                <a href="addmember.php"><button class="btn_addmember">Thêm thành viên <i class="fas fa-plus-circle"></i></button></a>
                <table class="table table-striped table-class" id= "table-id">  
                    <thead>
                      <tr>
                        <th class="th-sm">Họ và tên
                        </th>
                        <th class="th-sm">Quê quán
                        </th>
                        <th class="th-sm">SĐT
                        </th>
                        <th class="th-sm">Phòng
                        </th>
                         <th class="th-sm">Thuộc nhà trọ
                        </th>
                        <th class="th-sm">Ngày bắt đầu thuê
                        </th>
                        <th class="th-sm">Hóa đơn
                        </th>
                        <th class="th-sm">Điều khiển
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        while ($members_post = mysqli_fetch_array($members_list, MYSQLI_ASSOC)) {
                          echo "<tr>\n";
                          echo "<td>".$members_post["ten_members"]."</td>\n";
                          echo "<td>".$members_post["que_quan"]."</td>\n";
                          echo "<td>".$members_post["sdt"]."</td>\n";
                          echo "<td>".$members_post["phong"]."</td>\n";
                          echo "<td>".$members_post["tennhatro"]."</td>\n";
                          echo "<td>".$members_post["ngaybd"]."</td>\n";
                          echo "<td><a href=\"addbill.php?id=".$members_post["id_members"]."\" title=\"Tạo hóa đơn mới\"><button class=\"btn_taohoadon\">Tạo</button></a></td>\n";
                          echo "<td><a href=\"edit_member.php?id=".$members_post["id_members"]."\" title=\"Edit\"><i class=\"fas fa-edit\"></i></a><a style=\"color: red; margin-left: 1em\" href=\"delete_member.php?id=".$members_post["id_members"]."\" title=\"Delete\" onClick=\"return confirm('Are you sure you want to delete?');\"><i class=\"fas fa-trash-alt\"></i></a></td>\n";
                          echo "</tr>";
                        }
                    ?>
                    </tbody>

                </table>
                <div class='pagination-container'>
                    <nav>
                        <ul class="pagination">
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- End table -->
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

  <script  src="../js/table.js"></script>

</body>

</html>
