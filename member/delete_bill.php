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
            $query_delete_member = "DELETE FROM bills WHERE id_bill = '".$_REQUEST['id']."'";
            $conn->query($query_delete_member);
            header("Location:./bills.php");
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