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
        }
        else {
          echo " " . $conn->error;
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
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <script type="text/javascript">
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1;
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd='0'+dd
        } 

    if(mm<10) {
        mm='0'+mm
        } 

    today = dd+'-'+mm+'-'+yyyy;


    function saveFormAsTextFile()
        {
        var textToSave = '===================================================\r\n'+
        'Hóa Đơn Nhà Trọ: '+'<?php echo $tennhatro ?>\r\n'+
        '===================================================\r\n'+
        '\r\n'+
        '🔰 Phòng <?php echo $phong?> - <?php echo $hoten ?>\r\n'+
        '⚪ Ngày BĐ: <?php echo $ngaybd ?> ⚪ Ngày KT: <?php echo $ngaykt ?>\r\n'+
        '===================================================\r\n'+
        '\r\n'+
        '⚪ Số điện: <?php echo $sodien ?> x <?php echo $giadien ?> (VNĐ/KWh)\r\n'+
        '\r\n'+
        '⚪ Số Nước: <?php echo $sonuoc ?> x <?php echo $gianuoc ?> (VNĐ/m3)\r\n'+
        '\r\n'+
        '⚪ Giá Phòng: <?php echo $giaphong ?> (VNĐ/Tháng)\r\n'+
        '\r\n'+
        '⚪ Phụ thu: <?php echo $phuthu ?> (VNĐ/Tháng)\r\n'+
        '===================================================\r\n'+
        '\r\n'+
        '⚪ Ghi chú: <?php echo $ghichu ?>\r\n'+
        '===================================================\r\n'+
        '\r\n'+
        '▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫\r\n'+
        'Tổng tiền: <?php echo $tongtien ?> (VNĐ/Tháng)\r\n'+
        '▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫▫\r\n'+
        '\r\n'+
        '===================================================\r\n'+
        'Hóa đơn in ngày: ' + today;

        var textToSaveAsBlob = new Blob([textToSave], {type:"text/plain"});
        var textToSaveAsURL = window.URL.createObjectURL(textToSaveAsBlob);
        var fileNameToSaveAs = 'P<?php echo $phong?> - <?php echo $ngaykt ?> - <?php echo $hoten ?>.txt';

        var downloadLink = document.createElement("a");
        downloadLink.download = fileNameToSaveAs;
        downloadLink.innerHTML = "Download File";
        downloadLink.href = textToSaveAsURL;
        downloadLink.onclick = destroyClickedElement;
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();

        setTimeout(function() {
          window.close();
        }, 3000);
        
        }

    function destroyClickedElement(event)
        {
        document.body.removeChild(event.target);
        }
  </script>

</head>

<body onload="saveFormAsTextFile();">
</body>

</html>
