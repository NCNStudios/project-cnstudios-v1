<?php 
require_once '../connect/connect.php';
require_once '../lib/login/google-login/config.php';
require_once '../lib/login/google-login/User.class.php';
mysqli_set_charset($conn, 'UTF8');

//Hiển thị nội dung web
    $query_get_list_street= "SELECT * FROM polylines WHERE status = 'Verified'";
    $street_list = mysqli_query($conn, $query_get_list_street);

if(isset($_REQUEST["id"]))
            {
              $query_getinfo_home = "SELECT * FROM homes WHERE id_home = '".$_REQUEST['id']."'";
              $datahome = $conn->query($query_getinfo_home);

              if ($datahome->num_rows > 0) {
                  while($row = $datahome->fetch_assoc()) {
                      $uid = $row["oauth_uid"];
                      $tennhatro = $row["tennhatro"];
                      $vi_tri = $row["vi_tri"];
                      $gia_phong = $row["gia_phong"];
                      $gia_dien = $row["gia_dien"];
                      $gia_nuoc = $row["gia_nuoc"];
                      $phu_thu = $row["phu_thu"];
                      $mo_ta = $row["mo_ta"];
                      $khu_vuc = $row["khu_vuc"];
                      $anh_bia = $row["anh_bia"];
                      $date_modified = $row["date_modified"];
                      $coordinates = $row["coordinates"];
                      $id_home = $row["id_home"];                
                    }
              }
              else {
                  echo 'No result';
              }

              $query_getinfo_mem = "SELECT * FROM users WHERE oauth_uid = '$uid'";
              $datamem = $conn->query($query_getinfo_mem);

              if ($datamem->num_rows > 0) {
                  while($mem = $datamem->fetch_assoc()) {
                      $first_name = $mem["first_name"];
                      $last_name = $mem["last_name"];
                      $sdt_user = $mem["sdt_user"];
                      $email = $mem["email"];
                      $picture = $mem["picture"];
                      $mota_user = $mem["mota_user"];

                  }
              }
              else {
                  echo 'No result';
              }
              }
        else
        {
          header("Location:http://localhost:8080/NienLuan2/CNStudios");
        }

if(isset($_GET['code'])){
    $gClient->authenticate($_GET['code']);
    $_SESSION['token'] = $gClient->getAccessToken();
    header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
}

if(isset($_SESSION['token'])){
    $gClient->setAccessToken($_SESSION['token']);
}

if($gClient->getAccessToken()){

    $gpUserProfile = $google_oauthV2->userinfo->get();
    
    // Initialize User class
    $user = new User();
    
    // Getting user profile info
    $gpUserData = array();
    $gpUserData['oauth_uid']  = !empty($gpUserProfile['id'])?$gpUserProfile['id']:'';
    $gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:'';
    $gpUserData['last_name']  = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:'';
    $gpUserData['email']      = !empty($gpUserProfile['email'])?$gpUserProfile['email']:'';
    $gpUserData['gender']     = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:'';
    $gpUserData['locale']     = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:'';
    $gpUserData['picture']    = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:'';
    $gpUserData['link']       = !empty($gpUserProfile['link'])?$gpUserProfile['link']:'';
    
    // Insert or update user data to the database
    $gpUserData['oauth_provider'] = 'google';
    $userData = $user->checkUser($gpUserData);
    
    // Storing user data in the session
    $_SESSION['userData'] = $userData;

    $sql = "SELECT rank FROM users where oauth_uid = '".$userData['oauth_uid']."'";
    $rank = $conn->query($sql);

    if ($rank->num_rows > 0) {
        while($row = $rank->fetch_assoc()) {
            $usr_role = $row["rank"];
        }
    } else {
        echo 'No result';
    }

    

   echo '<style type="text/css">
                .site-menu #signin, .site-mobile-menu .site-mobile-menu-body .site-nav-wrap #signin{
                  visibility: hidden !important;
                  display: none !important;
                }

                .site-menu #signout, .site-mobile-menu .site-mobile-menu-body .site-nav-wrap #signout{
                    display: inline-block !important;
                }
                .site-menu #signout img, .site-mobile-menu .site-mobile-menu-body .site-nav-wrap #signout img{
                    border-radius: 50px;
                    padding: 2px;
                    background-color: rgb(255, 255, 255);
                    width: 3em;
                    height: 3em;
                    margin-left: 1em;
                }
              </style>';
    mysqli_close($conn);
}else{

echo '<style type="text/css">
        .site-menu #signin, .site-mobile-menu .site-mobile-menu-body .site-nav-wrap #signin{
          display: inline-block !important;
        }

        .site-menu #signout, .site-mobile-menu .site-mobile-menu-body .site-nav-wrap #signout{
          display: none !important;
        }
</style>';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Nhà trọ sinh viên - CNStudios.tk</title>
    <link rel="icon" type="image/png" href="../images/home_marker.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Roboto+Mono:300,400,500"> 
    <link rel="stylesheet" href="../fonts/icomoon/style.css">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/magnific-popup.css">
    <link rel="stylesheet" href="../css/jquery-ui.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.min.css">
    <link rel="stylesheet" href="../css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="../css/mediaelementplayer.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="../fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="../css/fl-bigmug-line.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- MAP -->
    <!-- Leaflet CDN -->
    <link rel="stylesheet" href="../lib/map/css/leaflet.css" />
    <script src="../lib/map/js/leaflet.js"></script>
    <link rel="stylesheet" href="../lib/map/css/style.css" />

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Fullscreen -->
    <script src='../lib/map/js/Leaflet.fullscreen.min.js'></script>
    <link href='../lib/map/css/leaflet.fullscreen.css' rel='stylesheet' />


    <!-- Locate -->
    <script src="../lib/map/js/L.Control.Locate.js"></script>
    <link rel="stylesheet" href="../lib/map/css/L.Control.Locate.min.css" />

    <!-- Search-->
    <script src="../lib/map/js/esri-leaflet.js"></script>
    <script src="../lib/map/js/esri-leaflet-geocoder.js"></script>
    <link rel="stylesheet" type="text/css" href="../lib/map/css/esri-leaflet-geocoder.css">
    
  </head>
  <body>
  
  <div class="site-loader"></div>
  
  <div class="site-wrap">

    <div class="site-mobile-menu">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->

    <div class="site-navbar">
        <div class="container py-1">
          <div class="row align-items-center">
            <div class="col-8 col-md-8 col-lg-4">
              <h1 class="mb-0"><a href="index.html" class="text-white h2 mb-0">
                <strong>CNStudios<span class="text-danger">.tk</span></strong> <img src="../images/home_marker.png" style="width: 1em"></a></h1>
            </div>
            <div class="col-4 col-md-4 col-lg-8">
              <nav class="site-navigation text-right text-md-right" role="navigation">

                <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

                <ul class="site-menu js-clone-nav d-none d-lg-block">
                  <li class="active">
                    <a href="./">Trang chủ</a>
                  </li>
                  <li><a href="">Diễn đàn</a></li>
                  <li><a href="">Dev tools</a></li>
                  <li><a href="./">Nhà trọ</a></li>
                  <li><a href="../contact.php">Contact</a></li>
                  <li id="signin">
                    <a href="../lib/login/google-login/"><img src="../images/google_signin2.png" title="Google login"></a>
                  </li>
                  <li class="has-children" id="signout">
                    <img src="<?php echo $userData['picture'] ?>">
                    <ul class="dropdown arrow-top">
                      <li><a><i class="fas fa-user"></i> <?php echo $userData['first_name'] ?><strong> <?php echo $userData['last_name'] ?></strong></a></li>
                      <li><a href="../<?php echo $usr_role ?>" style="color: rgb(102, 156, 25)"><i class="fas fa-tachometer-alt"></i> Your Dashboard</a></li>
                      <li><a><i class="fas fa-star-half-alt"></i> <?php echo $usr_role ?></a></li>
                      <li><a href="../lib/login/google-login/" style="color: #ff0000"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                  </li>
                </ul>
              </nav>
            </div>
           

          </div>
        </div>
      </div>
    </div>

    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(../images/bg.png);" data-aos="fade" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <span class="d-inline-block text-white px-3 mb-3 property-offer-type rounded">Property Details of</span>
            <h1 class="mb-2"><?php echo $tennhatro ?></h1>
            <p class="mb-5"><strong class="h2 text-danger font-weight-bold"><?php echo $gia_phong ?> /Tháng</strong></p>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section site-section-sm">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div>
              <div class="slide-one-item home-slider owl-carousel">
                <div><img src="<?php echo $anh_bia ?>" alt="Image" class="img-fluid"></div>
              </div>
            </div>
            <div class="bg-white property-body border-bottom border-left border-right">
              <div class="row mb-5">
                <div class="col-md-8">
                  <strong class="text-success h1 mb-3"><?php echo $gia_phong ?>/Tháng</strong>
                  <br/>
                  <small><strong class="text-danger"><i class="fas fa-map-marker"></i> <?php echo $vi_tri ?></strong></small>
                  <br/>
                  <small><strong class="text-primary"><i class="fas fa-clock"></i> <?php echo $date_modified ?></strong></small>
                  <br/><br/>
                </div>
                <div class="col-md-4">
                  <ul class="property-specs-wrap mb-3 mb-lg-0  float-lg-right">
                  <li>
                    <span class="property-specs">Khu vực</span>
                    <span class="property-specs-number"><?php echo $khu_vuc ?></span>
                    
                  </li>
                </ul>
                </div>
              </div>
              <div class="row mb-5">
                <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                  <span class="d-inline-block text-black mb-0 caption-text">Giá điện (đồng/KWh)</span>
                  <strong class="d-block"><?php echo $gia_dien ?></strong>
                </div>
                <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                  <span class="d-inline-block text-black mb-0 caption-text">Giá nước (đồng/m<sup>3</sup>)</span>
                  <strong class="d-block"><?php echo $gia_nuoc ?></strong>
                </div>
                <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                  <span class="d-inline-block text-black mb-0 caption-text">Phụ thu (Wifi,...)</span>
                  <strong class="d-block"><?php echo $phu_thu ?></strong>
                </div>
              </div>
              <h2 class="h4 text-black">Thông tin</h2>
              <p><?php echo $mo_ta ?></p>

            </div>
          </div>
          <div class="col-lg-4">

            <!-- Map -->
            <div class="map_embed" style="width: 100%; height: 31em">
                <div id="mapDiv" style="height: 40em; position: relative; top: 0; cursor: move; border: 1px solid rgba(136, 136, 136, 0.18); border-radius: 5px;"></div>
            </div>

            <div class="bg-white widget border rounded">
              <h3 class="h4 text-black widget-title mb-3">Liên hệ chủ trọ</h3>
              <form action="" class="form-contact-agent">
                <div class="form-group">
                  <img src="<?php echo $picture ?>" style="    height: 150px;width: 150px;border: 2px solid rgb(124, 189, 30);border-radius: 77px;">
                </div>
                <div class="form-group">
                  <label for="name">Tên</label>
                  <input type="text" id="name" class="form-control" readonly value="<?php echo $first_name ?> <?php echo $last_name ?>">
                </div>
                <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" id="email" class="form-control" readonly value="<?php echo $email ?>">
                </div>
                <div class="form-group">
                  <label for="phone">SĐT</label>
                  <input type="text" id="phone" class="form-control" readonly value="<?php echo $sdt_user ?>">
                </div>
                <div class="form-group">
                  <input type="submit" id="phone" class="btn btn-primary" value="Send Message">
                </div>
              </form>
            </div>

            <div class="bg-white widget border rounded">
              <h3 class="h4 text-black widget-title mb-3">Giới thiệu</h3>
              <p><?php echo $mota_user ?></p>
            </div>

          </div>
          
        </div>
      </div>
    </div>

    <div class="site-section site-section-sm" style="padding: 0em 0 !important;">

    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="mb-5">
              <h3 class="footer-heading mb-4">About CNstudios.tk</h3>
              <p>CNStudios là diễn đàn dành cho sinh viên và lập trình viên.<br/>
                CNStudios cung cấp các công cụ hỗ trợ lập trình, xử lý ảnh cho lập trình viên và những người cần sử dụng chúng một cách miễn phí.<br/>
                Ngoài ra, CNStudios còn cung cấp giải pháp lựa chọn nhà trọ cho sinh viên, bao gồm: Hệ thống tìm kiếm công khai, hệ thống quản lý nhà trọ online.
              </p>
            </div>

            
            
          </div>
          <div class="col-lg-4 mb-5 mb-lg-0">
            <div class="row mb-5">
              <div class="col-md-12">
                <h3 class="footer-heading mb-4">Danh mục</h3>
              </div>
              <div class="col-md-6 col-lg-6">
                <ul class="list-unstyled">
                  <li><a href="#">Home</a></li>
                  <li><a href="#">Diễn đàn</a></li>
                  <li><a href="#">Dev tools</a></li>
                  <li><a href="#">Nhà trọ</a></li>
                </ul>
              </div>
              <div class="col-md-6 col-lg-6">
                <ul class="list-unstyled">
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Privacy Policy</a></li>
                  <li><a href="../contact.php">Contact Us</a></li>
                  <li><a href="#">Terms</a></li>
                </ul>
              </div>
            </div>


          </div>

          <div class="col-lg-4 mb-5 mb-lg-0">
            <h3 class="footer-heading mb-4">Follow Us</h3>

                <div>
                  <a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                  <a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                  <a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                </div>

            

          </div>
          
        </div>
        <div class="row text-center">
          <div class="col-md-12">
            <p>

            Copyright &copy; All rights reserved by <a href="http://cnstudios.tk" target="_blank" >CNStudios</a>
            </p>
          </div>
          
        </div>
      </div>
    </footer>

  </div>

  <script src="../js/jquery-3.3.1.min.js"></script>
  <script src="../js/jquery-migrate-3.0.1.min.js"></script>
  <script src="../js/jquery-ui.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/owl.carousel.min.js"></script>
  <script src="../js/mediaelement-and-player.min.js"></script>
  <script src="../js/jquery.stellar.min.js"></script>
  <script src="../js/jquery.countdown.min.js"></script>
  <script src="../js/jquery.magnific-popup.min.js"></script>
  <script src="../js/bootstrap-datepicker.min.js"></script>
  <script src="../js/aos.js"></script>
  <script src="../js/circleaudioplayer.js"></script>

  <script src="../js/main.js"></script>

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


      var homeIcon = L.icon({
          iconUrl: '../images/home_marker.png',

          iconSize:     [30, 30], // size of the icon
          iconAnchor:   [15, 30] // point of the icon which will correspond to marker's location
      });
              var markers = [];
        function createMarker(coordinates, tennt, vitrint, idnt){
            var id;
            if (markers.length < 1) 
                id = 0;
            else 
                id = markers[markers.length - 1]._id + 1;

            var popupContent ='<center><div class="popupContent"><a class="pop_title" href="../detail/?id='+ idnt +'"> ' + tennt +'</a></div><div class="popupContent"><span class="text-success"> ' + vitrint +'</span></div><div class="popupContent"><p class="pop_title">Tọa độ:</p><span class="pop_content" id="span_' + id + '"> ' + coordinates +'</span></div><div class="pop_control"><button class="btn_ctrl copy" id="btn_' + id + '" onclick="copyCoord(' + id + ')" title="Copy this coordinate (Latitude/Longitude)"><i class="fas fa-copy"></i></button></div></center>';
            myMarker = L.marker(coordinates, {icon:homeIcon});
            myMarker._id = id;
            var myPopup = myMarker.bindPopup(popupContent, {closeButton: true});
            map.addLayer(myMarker);
            markers.push(myMarker);
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

        //Copy tọa độ vào clipboard
        function copyCoord(id){
            var copyText = document.getElementById("span_"+id);
            var textArea = document.createElement("textarea");
            textArea.value = copyText.textContent;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                var successful = document.execCommand('copy');
                var msg = successful ? 'Successfully' : 'Unsuccessfully';
                console.log(msg + ' copied to clipboard ');
            } catch (err) {
              console.log('Oops, unable to copy');
            }
            document.body.removeChild(textArea);
            document.getElementById("btn_"+id).innerHTML = "Copied!";
        }
    </script>

    <?php 
         echo "<script>
            map.setView([".$coordinates."], 13);
            createMarker([".$coordinates."], '".$tennhatro."', '".$vi_tri."', '".$id_home."');
            
         </script>";
    ?>

    <?php 

      while ($streets = mysqli_fetch_array($street_list, MYSQLI_ASSOC)) {
         echo "<script>
            createPolyline([".$streets["start_coord"]."],[".$streets["end_coord"]."],'".$streets["name_polyline"]."');
         </script>";
       }
    ?>
    
  </body>
</html>