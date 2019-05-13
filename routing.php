<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Hệ thống chỉ đường - CNStudios.tk</title>
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

    <!-- MAP -->
    <!-- Leaflet CDN -->
    <link rel="stylesheet" href="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.css" />
    <script src="https://npmcdn.com/leaflet@1.0.0-rc.2/dist/leaflet.js"></script>

    <!-- Main CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="lib/map/css/routing.css">

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

    <!-- routing -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    
  </head>
  <style>
    .title, .subTitle{
      color: #97ff00;
      padding-top: 1em;
      padding-left: 1em;
    }

    .detail {
      padding-left: 2.2em;
      padding-right: 2.2em;
      color: #fff;
    }

    .marker{
      color: #ff0000;
      font-size: 20px;
    }
  </style>
  <body>
  
  <div class="site-loader"></div>

    <!-- Map header -->
    <div class="map_embed">
        <div id="mapDiv" ></div>
        <a id="question" class="venobox_custom" data-vbtype="inline" href="#tip"></a>
        <div id="tip" class="tip">
          <h5 class="title"><i class="fas fa-cog fa-spin"></i> How to use?</h5>
          <p class="detail"><i class="fas fa-circle-notch fa-spin"></i> Chọn bất kỳ 2 địa điểm nào trên bản đồ.</p>
          <p class="detail"><i class="fas fa-circle-notch fa-spin"></i> Hệ thống sẽ chỉ ra đường đi ngắn nhất đến địa điểm đó.</p>
          <p class="detail"><i class="fas fa-circle-notch fa-spin"></i> Đồng thời, chi tiết chỉ dẫn đường đi sẽ hiển thị bên bảng thông tin chỉ đường phía bên phải.</p>
          <h5 class="subTitle"><i class="fas fa-cog fa-spin"></i> Đường đi được chỉ dẫn không như bạn mong muốn? Bạn có thể:</h5>
          <p class="detail"><i class="fas fa-circle-notch fa-spin"></i>  Kéo thả icon <i class="marker fas fa-map-marker-alt"></i> đến một vị trí khác để thay đổi địa điểm.</p>
          <p class="detail"><i class="fas fa-circle-notch fa-spin"></i> Trên đường đi mà hệ thống đã chỉ ra, ở bất kì vị trí nào, kéo thả đến đoạn đường mà bạn mong muốn đi qua. Hệ thống sẽ đưa ra cho bạn lựa chọn mới cho đường đi giữa 2 địa điểm.</p>
        </div>
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
        framewidth: '90%',   
        frameheight: '90%', 
        border: '2px', 
        bgcolor: '#555', 
        titleattr: 'data-title',
    });

    });
  </script>

  <!-- CREATE MAP -->
    <script>
        //Load map google
        var googleStreets = new L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{minZoom:1, maxZoom:19, subdomains:['mt0','mt1','mt2','mt3']});

        var googleSat = new L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{minZoom:1, maxZoom: 21,subdomains:['mt0','mt1','mt2','mt3']});

        var openSM = new L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

        var map = new L.Map('mapDiv', { doubleClickZoom:false, zoomControl:true, maxBounds:([[90,-270],[-90,270]]) });

        L.control.layers({"Google Street": googleStreets, "Google Earth": googleSat, "OpenStreetMap": openSM}).addTo(map);

        map.addLayer(googleStreets); //Mặc định load GoogleStreet Map
        var map_set = "googleStreets";
        map.fitBounds([[0,-180],[0,180]]);
        map.setView([10.03056, 105.76521], 13); //Tọa độ mặc định ban đầu (Cần Thơ | Zoom 13)

        //Thêm nút Locate
        L.control.locate({strings: {title: "Show me where I am"}}).addTo(map);

        
    </script>

    <!-- THÊM ĐIỂM -->
    <script>
        var markers = [];
        function createMarker(coordinates){
            var id;
            if (markers.length < 1) 
                id = 0;
            else 
                id = markers[markers.length - 1]._id + 1;

            var popupContent ='<center><div class="popupContent"><p class="pop_title">Coordinates:</p><span class="pop_content" id="span_' + id + '"> ' + coordinates +'</span></div><div class="pop_control"><button class="btn_ctrl copy" id="btn_' + id + '" onclick="copyCoord(' + id + ')" title="Copy this coordinate (Latitude/Longitude)"><i class="fas fa-copy"></i></button><button class="btn_ctrl delete" onclick="clearMarker(' + id + ')" title="Delete this marker"><i class="fas fa-trash"></i></button></div></center>';
            myMarker = L.marker(coordinates, {draggable: true});
            myMarker._id = id;
            var myPopup = myMarker.bindPopup(popupContent, {closeButton: true});
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
        var c1_lat;
        var c1_lon;
        var c2_lat;
        var c2_lon;
        var count = 0;
        function onMapClick(e) {
            if(count < 2){
                var lat  = e.latlng.lat.toFixed(5);
                var lon  = e.latlng.lng.toFixed(5);
                createMarker([lat, lon]);
                count++;
                if (count == 1){
                    c1_lat = lat;
                    c1_lon = lon;
                }
                if (count == 2){
                    c2_lat = lat;
                    c2_lon = lon;
                    L.Routing.control({
                      waypoints: [
                        L.latLng(c1_lat, c1_lon),
                        L.latLng(c2_lat, c2_lon)
                      ]
                    }).addTo(map);
                    clearMarker(0);
                    clearMarker(1);
                }
            }
        }

        map.on('click', onMapClick);    
    </script>

     <script>
      var customControl =  L.Control.extend({

      options: {
        position: 'topleft'
      },

      onAdd: function (map) {
        var container = L.DomUtil.create('input');
        container.type="button";
        container.title="How to use?";

        container.style.backgroundColor = 'white';     
        container.style.backgroundImage = "url(./images/question.png)";
        container.style.backgroundSize = "24px 24px";
        container.style.width = '30px';
        container.style.height = '30px';
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
          console.log('Button Home Clicked');
          document.getElementById('question').click();
        }

        return container;
      }

    });

      map.addControl(new customControl());
    </script>

    <script>
      var customControl =  L.Control.extend({

      options: {
        position: 'topleft'
      },

      onAdd: function (map) {
        var btnReload = L.DomUtil.create('input');
        btnReload.type="button";
        btnReload.title="Reload map";

        btnReload.style.backgroundColor = 'white';     
        btnReload.style.backgroundImage = "url(./images/reload.png)";
        btnReload.style.backgroundSize = "24px 24px";
        btnReload.style.width = '30px';
        btnReload.style.height = '30px';
        btnReload.style.borderRadius = '5px';
        btnReload.style.backgroundRepeat = 'no-repeat';
        btnReload.style.padding = '5px';

        btnReload.onmouseover = function(){
          btnReload.style.cursor = 'pointer';
        }
        btnReload.onmouseout = function(){
          btnReload.style.backgroundColor = 'white';
        }

        btnReload.onclick = function(){
          console.log('Button Home Clicked');
          location.reload();
        }

        return btnReload;
      }
    });

      map.addControl(new customControl());


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
    </script>
  </body>
</html>