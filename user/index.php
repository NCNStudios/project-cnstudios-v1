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

    if($rank != "User"){
      header("Location:http://localhost:8080/NienLuan2/CNStudios");
    }
    else{

        $sql = "SELECT * FROM users where oauth_uid = '".$userData['oauth_uid']."'";

        $data = $conn->query($sql);

        if ($data->num_rows > 0) {
            while($row = $data->fetch_assoc()) {
                $first_name = $row["first_name"];
                $email = $row["email"];
                $last_name = $row["last_name"];
                $picture = $row["picture"];
                $link = $row["link"];
            }
        } else {
            echo 'No result';
        }

      mysqli_close($conn);
    }
}
else{
  header("Location:http://localhost:8080/NienLuan2/CNStudios");
}
?>
<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Nhà trọ sinh viên - CNStudios.tk</title>
  <link rel="icon" type="image/png" href="../images/home_marker.png">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
<link  rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

  
      <link rel="stylesheet" href="css/style.css">

  
</head>

<body>

<aside class="profile-card">

    <header>
    
        <!-- here’s the avatar -->
        <a href="<?php echo $link ?>">
            <img src="<?php echo $picture ?>" />
        </a>
        
        <!-- the username -->
        <h1><?php echo $first_name ?> <?php echo $last_name ?></h1>
        
        <h2 class="text-danger"><?php echo $email ?></h2>
    
    </header>

    <!-- bit of a bio; who are you? -->
    <div class="profile-bio">
    
        <p>CNStudios.tk - Diễn đàn hỗ trợ sinh viên IT và hệ thống tìm kiếm nhà trọ tốt nhất cho sinh viên.
            Nếu bạn là chủ nhà trọ, vui lòng <a href="../contact.php">LIÊN HỆ</a> với Admin để xác minh tài khoản của bạn.</p>
    
    </div>

    <!-- some social links to show off -->
    <div class="profile-social-links">
        <center>
                <a href="../" title="Go home">
                    <img src="../images/home_marker.png" width="30em" height="30em">
                </a>

        </center>
    </div>

</aside>
<!-- that’s all folks! -->
  
  

</body>

</html>
