<?php 
require_once 'connect/connect.php';
require_once 'lib/login/google-login/config.php';
require_once 'lib/login/google-login/User.class.php';
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
    <link rel="icon" type="image/png" href="../images/home_marker.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Roboto+Mono:300,400,500"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

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
    
  
    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">
    
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
              <h1 class="mb-0"><a href="index.php" class="text-white h3 mb-0">
                <strong>CNStudios<span class="text-danger">.tk</span></strong> <img src="images/home_marker.png" style="width: 1em"></a></h1>
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

    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(http://victory-design.ru/sandbox/codepen/profile_card/bg.png);" data-aos="fade" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <h1 class="mb-2">Contact Us</h1>
          </div>
        </div>
      </div>
    </div>
    

    <div class="site-section">
      <div class="container">
        <div class="row">
       
          <div class="col-md-12 col-lg-8 mb-5">
          
            
          
            <form action="#" class="p-5 bg-white border">

              <div class="row form-group">
                <div class="col-md-12 mb-3 mb-md-0">
                  <label class="font-weight-bold" for="fullname">Full Name</label>
                  <input type="text" id="fullname" class="form-control" placeholder="Full Name">
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-12">
                  <label class="font-weight-bold" for="email">Email</label>
                  <input type="email" id="email" class="form-control" placeholder="Email Address">
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-12">
                  <label class="font-weight-bold" for="email">Subject</label>
                  <input type="text" id="subject" class="form-control" placeholder="Enter Subject">
                </div>
              </div>
              

              <div class="row form-group">
                <div class="col-md-12">
                  <label class="font-weight-bold" for="message">Message</label> 
                  <textarea name="message" id="message" cols="30" rows="5" class="form-control" placeholder="Say hello to us"></textarea>
                </div>
              </div>

              <div class="row form-group">
                <div class="col-md-12">
                  <input type="submit" value="Send Message" class="btn btn-primary  py-2 px-4 rounded-0">
                </div>
              </div>

  
            </form>
          </div>

          <div class="col-lg-4">
            <div class="p-4 mb-3 bg-white">
              <h3 class="h6 text-black mb-3 text-uppercase">Contact Info</h3>
              <p class="mb-0 font-weight-bold">Admin</p>
              <p class="mb-4">Arron Smith (Mít Admin)</p>

              <p class="mb-0 font-weight-bold">Phone</p>
              <p class="mb-4"><a href="#">+84 945 665 161</a></p>

              <p class="mb-0 font-weight-bold">Email Address</p>
              <p class="mb-0"><a href="#">Mrnpro321@gmail.com</a></p>

            </div>
            
          </div>
        </div>
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
  <script src="js/circleaudioplayer.js"></script>

  <script src="js/main.js"></script>
    
  </body>
</html>