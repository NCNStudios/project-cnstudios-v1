<?php 
require_once '../connect/connect.php';
require_once '../lib/login/google-login/config.php';
require_once '../lib/login/google-login/User.class.php';
date_default_timezone_set("Asia/Ho_Chi_Minh");
mysqli_set_charset($conn, 'UTF8');

//Hiển thị nội dung web
    $query_get_list_street= "SELECT * FROM polylines WHERE status = 'Verified'";
    $street_list = mysqli_query($conn, $query_get_list_street);

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
      $tenduong = "";
      $toadobd = "";
      $toadokt = "";
      $uid = "".$userData['oauth_uid'];
      $date_modified = (new \DateTime())->format('Y-m-d H:i:s');


      //Lấy giá trị POST từ form vừa submit (tenduong toado1 toado2)
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          if(isset($_POST["tenduong"])) { $tenduong = $_POST['tenduong'];  }
          if(isset($_POST["toado1"])) { $toadobd = $_POST['toado1']; }
          if(isset($_POST["toado2"])) { $toadokt = $_POST['toado2']; }

          $sql2 = "INSERT INTO polylines (oauth_uid, name_polyline, start_coord, end_coord, date_modified) VALUES ('$uid', '$tenduong', '$toadobd', '$toadokt', '$date_modified')"; 

          if ($conn->query($sql2) == TRUE) {
             header("Location:./addhome.php");
          } else {
             header("Location:http://localhost:8080/NienLuan2/CNStudios/a");
          }
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
  <link rel="stylesheet" href="../lib/map/css/style.css">

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <link href="../lib/summernote/summernote-lite.css" rel="stylesheet">
  <script src="../lib/summernote/summernote-lite.js"></script>

  <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
  <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />

  <!-- Leaflet CDN -->
    <link rel="stylesheet" href="../lib/map/css/leaflet.css" />
    <script src="../lib/map/js/leaflet.js"></script>

  <!-- Locate -->
    <script src="../lib/map/js/L.Control.Locate.js"></script>
    <link rel="stylesheet" href="../lib/map/css/L.Control.Locate.min.css" />

    <!-- Fullscreen -->
    <script src='../lib/map/js/Leaflet.fullscreen.min.js'></script>
    <link href='../lib/map/css/leaflet.fullscreen.css' rel='stylesheet' />

    <!-- Search-->
    <script src="../lib/map/js/esri-leaflet.js"></script>
    <script src="../lib/map/js/esri-leaflet-geocoder.js"></script>
    <link rel="stylesheet" type="text/css" href="../lib/map/css/esri-leaflet-geocoder.css">

  <script type="text/javascript">
        var developerKey = 'AIzaSyCEz_36sBS69ywynFXxhPdVych3ifLc5RU';
        var clientId = "1049476838291-f4aukrotargdgbjaasakdmvqt57p9u4n.apps.googleusercontent.com"
        var appId = "1049476838291";
        var scope = ['https://www.googleapis.com/auth/drive'];
        var pickerApiLoaded = false;
        var oauthToken;

        function loadPicker() {
          gapi.load('auth', {'callback': onAuthApiLoad});
          gapi.load('picker', {'callback': onPickerApiLoad});
          loadingImage();
        }

        function onAuthApiLoad() {
          window.gapi.auth.authorize(
              {
                'client_id': clientId,
                'scope': scope,
                'immediate': false
              },
              handleAuthResult);
        }

        function onPickerApiLoad() {
          pickerApiLoaded = true;
          createPicker();
        }

        function handleAuthResult(authResult) {
          if (authResult && !authResult.error) {
            oauthToken = authResult.access_token;
            createPicker();
          }
        }

        function createPicker() {
        var view = new google.picker.View(google.picker.ViewId.DOCS);
        var folder = new google.picker.View(google.picker.ViewId.FOLDERS);
        view.setMimeTypes('image/png,image/jpeg');

        var uploadView = new google.picker.DocsUploadView();

        var picker = new google.picker.PickerBuilder().
            addView(uploadView).
            addView(view).
            setAppId(appId).
            setOAuthToken(oauthToken).
            setCallback(pickerCallback).
            build();
        picker.setVisible(true);
    }
        function pickerCallback(data) {
            if (data.action == google.picker.Action.PICKED) {
                var fileId = data.docs[0].id;
            document.getElementById("gd_thumbnail").value = "https://drive.google.com/uc?id="+ fileId;
            changeimageURL();
            }
        }
    </script>
</head>
<style>
    .content .card .card-body .line {
      width: 100%;
      height: 1px;
      border-bottom: 1px solid #ddd;
      margin: 15px 0;
    }
    #img_preview{
        width: 100%;
        height: 150px;
        background: linear-gradient(to bottom right, #a044ff, #e73827,  #2C3E50, #FD746C, #FF8235, #ffff1c, #92FE9D, #00C9FF );
        background-repeat: no-repeat;
        background-size: 1000% 1000%;
        animation: gradient 60s ease infinite;
        border-radius: 10px;
        border: 1.5px solid rgb(255, 87, 34);
        box-shadow: 0 4px 20px 0px rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(156, 39, 176, 0.4);
    }
    #upload_thumbnail{
        border: 1px solid rgb(202, 202, 202);
        padding: 15px;
        border-radius: 10px;
        margin: 0 0 10px 0; 
    }
    
    .GDUpload{
        background: rgb(0, 123, 255);
        text-align: center;
        padding: 8px;
        color: rgb(255, 255, 255);
        border-radius: 4px;
        outline: none;
        transition: 0.15s;
    }

    .GDUpload:hover{
        background: rgb(1, 60, 255);
    }

    @keyframes gradient { 
      0%{background-position:0% 0%}
      50%{background-position:100% 100%}
      100%{background-position:0% 0%}
    }
  </style>

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
          <li class="nav-item">
            <a class="nav-link" href="./pricelist.php">
              <i class="far fa-list-alt"></i>
              <p>Bảng giá dịch vụ</p>
            </a>
          </li>
          <li class="nav-item active">
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
            <a class="navbar-brand" href="#pablo">Thêm đường không có trên bản đồ</a>
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
                  <h4 class="card-title">Thêm đường không có trên bản đồ</h4>
                  <p class="card-category">Đường mới sẽ được đăng tải sau khi xét duyệt!</p>
                </div>
                <div class="card-body">
                  <form action="" method="POST" id="frm_upload">

                    <label class="font-weight-bold" for="exampleFormControlFile1">Thêm đường</label>
                    <div class="map_embed" style="width: 100%; height: 43em;">

                      <div id="mapDiv" style="height: 50em; position: relative; top: 0; cursor: crosshair; border-radius: 1em"></div>
                    </div>
                   <br>

                    <div class="form-group">
                        <input name="tenduong" type="text" class="form-control" id="tenduong" placeholder="Tên đường...">
                    </div>
                    <div class="form-row">
                      <div class="form-group col-md-6">
                          <input name="toado1" type="text" class="form-control" id="toado1" placeholder="Tọa độ điểm bắt đầu" readonly>
                      </div>
                      <div class="form-group col-md-6">
                          <input name="toado2" type="text" class="form-control" id="toado2" placeholder="Tọa độ điểm kết thúc" readonly>
                      </div>
                    <div class="line"></div>

                  <button name="video-upload" type="submit" class="btn btn-primary" style="width: 10em; margin-left: 2em; margin-top: 2em;">Thêm  <i class="fas fa-plus"></i></button>
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

  <!-- CREATE MAP -->
    <script>
        //Load map google
        var googleStreets = new L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{minZoom:1, maxZoom:19, subdomains:['mt0','mt1','mt2','mt3']});

        var googleSat = new L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{minZoom:1, maxZoom: 21,subdomains:['mt0','mt1','mt2','mt3']});

        var openSM = new L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

        var streets = L.layerGroup();

        var map = new L.Map('mapDiv', { 
          doubleClickZoom:false,
          fullscreenControl: true, 
          zoomControl:true, 
          maxBounds:([[90,-270],[-90,270]]),
          layers: [googleStreets, streets]
        });

        map.fitBounds([[0,-180],[0,180]]);
        map.setView([10.03056, 105.76521], 13); //Tọa độ mặc định ban đầu (Cần Thơ | Zoom 13)

        //Thêm nút Locate
        L.control.locate({strings: {title: "Show me where I am"}}).addTo(map);

        //Thêm nút Search
        var searchControl = new L.esri.Controls.Geosearch().addTo(map);
        var results = new L.LayerGroup().addTo(map);
        searchControl.on('results', function(data){
            results.clearLayers();
            for (var i = data.results.length - 1; i >= 0; i--) {
                var lat  = data.results[i].latlng.lat.toFixed(5);
                var lon  = data.results[i].latlng.lng.toFixed(5);
            }
        });
        setTimeout(function(){$('.pointer').fadeOut('slow');},3400);


        var homeIcon = L.icon({
            iconUrl: '../images/circle.png',

            iconSize:     [10, 10], 
            iconAnchor:   [5, 5]
        });

        var markers = [];
        function createMarker(coordinates){
            var id=1;

            myMarker = L.marker(coordinates, {icon:homeIcon});
            myMarker._id = id;
            map.addLayer(myMarker);
            markers.push(myMarker);
        }
        function clearMarker(id) {
            console.log(markers);
            var new_markers = [];
            markers.forEach(function(marker) {
                if (marker._id == id) 
                    map.removeLayer(marker);
                else 
                    new_markers.push(marker);
            });
          markers = new_markers;
        }

        var polylines = [];
        function createPolyline(start_coordinates, end_coordinates, street_name){
            var id2;
            if (polylines.length < 1) 
                id2 = 0;
            else 
                id2 = polylines[polylines.length - 1]._id + 1;

              polyline = new L.Polyline([start_coordinates, end_coordinates], {
                color: 'red',
                weight: 3,
                opacity: 0.8,
              }).bindPopup(""+street_name);

              polyline._id = id2;

            //Thêm điểm mới tạo vào layer group homes
            polyline.addTo(streets);

            //map.addLayer(myMarker);
            polylines.push(polyline);
        }


        //Tạo menu điều khiển bên phải
        var baseLayers = {
          "Google Street": googleStreets,
          "Google Earth": googleSat, 
          "OpenStreetMap": openSM
        };

        var overlayMaps = {
            "Đường": streets
        }

        L.control.layers(baseLayers, overlayMaps).addTo(map);


    </script>

    <!-- On Map Click -->
    <script>
      var i=0;
        function onMapClick(e) {
            i++;
            if (i==3) {
              clearMarker(1);
              i=1;
            }
            var lat  = e.latlng.lat.toFixed(5);
            var lon  = e.latlng.lng.toFixed(5);
            createMarker([lat, lon]);
            md.showNotification('top','center', lat, lon);
            if(i==1) {
              document.getElementById("toado1").value = ""+lat+", "+lon;
            }
            if(i==2){
              document.getElementById("toado2").value = ""+lat+", "+lon;
            }
            if(i==3) {
              document.getElementById("toado1").value = " ";
              document.getElementById("toado2").value = " ";
            }
        }
        map.on('click', onMapClick);    
    </script>

    <?php 

      while ($streets = mysqli_fetch_array($street_list, MYSQLI_ASSOC)) {
         echo "<script>
            createPolyline([".$streets["start_coord"]."],[".$streets["end_coord"]."],'".$streets["name_polyline"]."');
         </script>";
       }
    ?>

</body>

</html>
