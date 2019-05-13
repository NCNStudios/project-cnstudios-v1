<?php 
require_once 'connect/connect.php';
require_once 'lib/login/google-login/config.php';
require_once 'lib/login/google-login/User.class.php';
mysqli_set_charset($conn, 'UTF8');

//Hiển thị nội dung web
    $query_get_list_home = "SELECT * FROM homes WHERE status = 'Verified'";
    $home_list = mysqli_query($conn, $query_get_list_home);

    //Hiển thị nội dung web
    $query_get_list_coord = "SELECT * FROM homes WHERE status = 'Verified'";
    $coord_list = mysqli_query($conn, $query_get_list_coord);

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
}
else{

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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Roboto+Mono:300,400,500"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">
    <link rel="shortcut icon" type="image/png" href="images/home_marker.png"/>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/mediaelementplayer.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="css/fl-bigmug-line.css">
    <link rel="stylesheet" href="lib/venobox/venobox.css">
    
  
    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="lib/map/css/style.css">


    <!-- MAP -->
    <!-- Leaflet CDN -->
    <link rel="stylesheet" href="lib/map/css/leaflet.css" />
    <script src="lib/map/js/leaflet.js"></script>

    
    <!-- Locate -->
    <script src="lib/map/js/L.Control.Locate.js"></script>
    <link rel="stylesheet" href="lib/map/css/L.Control.Locate.min.css" />

    <!-- Fullscreen -->
    <script src='lib/map/js/Leaflet.fullscreen.min.js'></script>
    <link href='lib/map/css/leaflet.fullscreen.css' rel='stylesheet' />

    <!-- Search-->
    <script src="lib/map/js/esri-leaflet.js"></script>
    <script src="lib/map/js/esri-leaflet-geocoder.js"></script>
    <link rel="stylesheet" type="text/css" href="lib/map/css/esri-leaflet-geocoder.css">
    
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
    </div> <!-- mobile-menu -->

    <div class="site-navbar">
        <div class="container py-1">
          <div class="row align-items-center">
            <div class="col-8 col-md-8 col-lg-4">
              <h1 class="mb-0"><a href="index.php" class="text-white h3 mb-0">
                <strong>CNStudios<span class="text-danger">.tk</span></strong> <img src="images/home_marker.png" style="width: 1em"></a></h1>
            </div>
            <div class="col-4 col-md-4 col-lg-8">
              <nav class="site-navigation text-right text-md-right" role="navigation">

                <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

                <ul class="site-menu js-clone-nav d-none d-lg-block">
                  <li class="active">
                    <a href="">Trang chủ</a>
                  </li>
                  <li><a href="">Diễn đàn</a></li>
                  <li><a href="">Dev tools</a></li>
                  <li><a href="">Nhà trọ</a></li>
                  <li><a href="contact.php">Contact</a></li>
                  <li id="signin">
                    <a href="lib/login/google-login/"><img src="images/google_signin2.png" title="Google login"></a>
                  </li>
                  <li class="has-children" id="signout">
                    <img src="<?php echo $userData['picture'] ?>">
                    <ul class="dropdown arrow-top">
                      <li><a><i class="fas fa-user"></i> <?php echo $userData['first_name'] ?><strong> <?php echo $userData['last_name'] ?></strong></a></li>
                      <li><a href="<?php echo $usr_role ?>" style="color: rgb(102, 156, 25)"><i class="fas fa-tachometer-alt"></i> Your Dashboard</a></li>
                      <li><a><i class="fas fa-star-half-alt"></i> <?php echo $usr_role ?></a></li>
                      <li><a href="lib/login/google-login/" style="color: #ff0000"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                  </li>
                </ul>
              </nav>
            </div>
           

          </div>
        </div>
      </div>
    </div>

    <!-- Map header -->
    <div class="map_embed">
        <div id="mapDiv" ></div>
    </div>


    <div class="site-section site-section-sm pb-0">
        <div style="justify-content: center;">
          <form class="form-search col-md-12" style="margin-top: -100px;">
            <div class="row  align-items-end" style="justify-content: center;">
              <img src="images/down.gif" style="width: 4%; height: 8%">
            </div>  
          </form>
      </div>
    </div>

    <div class="site-section site-section-sm bg-light" id="main">
      <div class="container">
      
        <div class="row mb-5">
          <?php 
              while ($homes = mysqli_fetch_array($home_list, MYSQLI_ASSOC)) {
                echo "<div class=\"col-md-6 col-lg-4 mb-4\">\n";
                echo "<div class=\"property-entry\">\n";
                echo "<a href=\"detail/?id=".$homes["id_home"]."\" class=\"property-thumbnail\">\n";
                echo "<img src=\"".$homes["anh_bia"]."\" alt=\"Image\" style=\"width: 100%; height: 100%\">\n";
                echo "</a>\n";
                echo "<div class=\"p-4 property-body\">\n";
                echo "<a href=\"#\" class=\"property-favorite\"><span class=\"icon-heart-o\"></span></a>\n";
                echo "<h2 class=\"property-title\"><a href=\"detail/?id=".$homes["id_home"]."\">".$homes["tennhatro"]."</a></h2>\n";
                echo "<span class=\"property-location d-block mb-3\" style=\"color: #0099cc\"><span class=\"property-icon icon-room\"></span> ".$homes["vi_tri"]."</span>\n";
                echo "<strong class=\"property-price text-primary mb-3 d-block text-success\">".$homes["gia_phong"]." VNĐ/Tháng</strong>\n";
                echo "<ul class=\"property-specs-wrap mb-3 mb-lg-0\">\n";
                echo "<li>\n";
                echo "<span class=\"property-specs\">Loại trọ</span>\n";
                echo "<span class=\"property-specs-number\">".$homes["loai_nha_tro"]."</span>\n";
                echo "</li>\n";
                echo "<li> \n";
                echo "<span class=\"property-specs\">Khu vực</span>\n";
                echo "<span class=\"property-specs-number\">".$homes["khu_vuc"]."</span>\n";
                echo "</li>\n";
                echo "<li> \n";
                echo "<span class=\"property-specs\">Giá điện (đồng/KWh)</span>\n";
                echo "<span class=\"property-specs-number\">".$homes["gia_dien"]."</span>  \n";
                echo "</li>\n";
                echo "<li> \n";
                echo "<span class=\"property-specs\">Giá nước (đồng/m<sup>3</sup>)</span>\n";
                echo "<span class=\"property-specs-number\">".$homes["gia_nuoc"]."</span>\n";
                echo "</li>\n";
                echo "<li> \n";
                echo "<span class=\"property-specs\">Phụ thu (Wifi, giữ xe,...)</span>\n";
                echo "<span class=\"property-specs-number\">".$homes["phu_thu"]."</span>\n";
                echo "</li>\n";
                echo "</ul>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "</div>";
              }
          ?>
        </div>

        <!-- PAGE -->
        <!-- <div class="row">
          <div class="col-md-12 text-center">
            <div class="site-pagination">
              <a href="#" class="active">1</a>
              <a href="#">2</a>
              <a href="#">3</a>
              <a href="#">4</a>
              <a href="#">5</a>
              <span>...</span>
              <a href="#">10</a>
            </div>
          </div>  
        </div> -->
        
      </div>
    </div>
    

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
                  <li><a id="routing" class="venobox_custom" data-vbtype="iframe" href="routing.php">Hệ thống chỉ đường</a></li>
                </ul>
              </div>
              <div class="col-md-6 col-lg-6">
                <ul class="list-unstyled">
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Privacy Policy</a></li>
                  <li><a href="#">Contact Us</a></li>
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

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/mediaelement-and-player.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/bootstrap-datepicker.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
  <script src="lib/venobox/venobox.min.js"></script>

  <script>
    $(document).ready(function(){
        $('.venobox_custom').venobox({
        framewidth: '90%',        // default: ''
        frameheight: '90%',       // default: ''
        border: '2px',             // default: '0'
        bgcolor: '#555',         // default: '#fff'
        titleattr: 'data-title',    // default: 'title'
    });

    });
  </script>

    <!-- CREATE MAP -->
    <script>
        //Load map google
        var googleStreets = new L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{minZoom:1, maxZoom:19, subdomains:['mt0','mt1','mt2','mt3']});

        var googleSat = new L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{minZoom:1, maxZoom: 21,subdomains:['mt0','mt1','mt2','mt3']});

        var openSM = new L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

        var homes = L.layerGroup();
        var streets = L.layerGroup();

        var map = new L.Map('mapDiv', { 
          doubleClickZoom:false, 
          fullscreenControl: true, 
          zoomControl:true, 
          maxBounds:([[90,-270],[-90,270]]),
          layers: [googleStreets, homes, streets]
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
                // createMarker([lat, lon]);
            }
        });
        setTimeout(function(){$('.pointer').fadeOut('slow');},3400);

        //Thay icon mặc định
        var homeIcon = L.icon({
            iconUrl: 'images/home_marker.png',

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

            var popupContent ='<center><div class="popupContent"><a class="pop_title" href="detail/?id='+ idnt +'"> ' + tennt +'</a></div><div class="popupContent"><span class="text-success"> ' + vitrint +'</span></div><div class="popupContent"><p class="pop_title">Tọa độ:</p><span class="pop_content" id="span_' + id + '"> ' + coordinates +'</span></div><div class="pop_control"><button class="btn_ctrl copy" id="btn_' + id + '" onclick="copyCoord(' + id + ')" title="Copy this coordinate (Latitude/Longitude)"><i class="fas fa-copy"></i></button></div></center>';
            myMarker = L.marker(coordinates, {icon:homeIcon});
            myMarker._id = id;
            var myPopup = myMarker.bindPopup(popupContent, {closeButton: true});

            //Thêm điểm mới tạo vào layer group homes
            myMarker.addTo(homes);

            //map.addLayer(myMarker);
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

            polylines.push(polyline);
        }


        //Tạo menu điều khiển bên phải
        var baseLayers = {
          "Google Street": googleStreets,
          "Google Earth": googleSat, 
          "OpenStreetMap": openSM
        };

        var overlayMaps = {
            "Nhà trọ": homes,
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

    <script>
      var customControl =  L.Control.extend({

      options: {
        position: 'topright'
      },

      onAdd: function (map) {
        var container = L.DomUtil.create('input');
        container.type="button";
        container.title="Chỉ đường";
        container.id="venobox";

        container.style.backgroundColor = 'white';     
        container.style.backgroundImage = "url(https://data.apksum.com/9a/com.voyager.gps.maps.directions/1.9/icon.png)";
        container.style.backgroundSize = "47px 47px";
        container.style.width = '47px';
        container.style.height = '47px';
        container.style.borderRadius = '5px';
        container.style.backgroundRepeat = 'no-repeat';
        container.style.padding = '5px';

        container.onmouseover = function(){
          container.style.cursor = 'pointer';
        }
        container.onmouseout = function(){
          container.style.backgroundColor = 'white';
        }

        container.onclick = function(){
          console.log('Button routing clicked');
          document.getElementById('routing').click();
        }

        return container;
      }
    });

      map.addControl(new customControl());
    </script>

    <?php 

      while ($coords = mysqli_fetch_array($coord_list, MYSQLI_ASSOC)) {
         echo "<script>
            createMarker([".$coords["coordinates"]."], '".$coords["tennhatro"]."', '".$coords["vi_tri"]."', '".$coords["id_home"]."');
         </script>";
       }
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