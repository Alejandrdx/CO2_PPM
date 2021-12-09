<?php
session_start();
$_SESSION['logged'] = false;

$msg="";
$email="";

if(isset($_POST['email']) && isset($_POST['password'])) {

  if ($_POST['email']==""){
    $msg.="Debe ingresar un email <br>";
  }else if ($_POST['password']=="") {
    $msg.="Debe ingresar la clave <br>";
  }else {
    $email = strip_tags($_POST['email']);
    $password= sha1(strip_tags($_POST['password']));

    $conn = mysqli_connect("localhost","admin_co2iot","73896583","admin_co2iot");

    if ($conn==false){
      echo "Hubo un problema al conectarse a María DB";
      die();
    }

    $result = $conn->query("SELECT * FROM `user` WHERE `user_email` = '".$email."' AND  `user_password` = '".$password."' ");
    $users = $result->fetch_all(MYSQLI_ASSOC);

    $count = count($users);

    if ($count == 1){
      $_SESSION['user_id'] = $users[0]['user_id'];
      $_SESSION['user_email'] = $users[0]['user_email'];
      $_SESSION['logged'] = true;

      $result = $conn->query("SELECT * FROM `device` WHERE `device_user_id` = '".$users[0]['user_id']."'");
      $devices = $result->fetch_all(MYSQLI_ASSOC);
      $_SESSION['devices'] = $devices;

      echo '<meta http-equiv="refresh" content="2; url=dashboard.php">';
    }else{
      $msg .= "Acceso Denegado";
      $_SESSION['logged'] = false;
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Login</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="../assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="assets/images/logo.png">
  <link rel="stylesheet" href="assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />
  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />
  <style scoped>
    body {
      background-image: url("assets/images/wallpaper.jpg");
      background-position: center;
      background-size: cover;
      height: 100vh;
    }
  </style>
</head>
<body>
  <div class="app" id="app">
    <div class="center-block w-xxl w-auto-xs p-y-md">
      <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
        <div class="m-b text-sm-center">
          <h2 class=" _700 l-s-n-1x m-b-md text-dark">LOG<span class="text-danger">IN</span></h2>
        </div>
        <form target="" method="post" name="form">
          <div class="md-form-group float-label">
            <input name="email" type="email" class="md-input" value="<?php echo $email ?>" ng-model="user.email" required >
            <label>Email</label>
          </div>
          <div  class="md-form-group float-label">
            <input name="password" type="password" class="md-input" ng-model="user.password" required >
            <label>Password</label>
          </div>
          <button type="submit" class="btn dark btn-block p-x-md">Login</button>
        </form><br>
        <div class="m-b text-sm-center">
          <span class="text-danger">
            <?php echo $msg ?>
          </span>
        </div>
        <div class="p-v-lg text-center">
          <a ui-sref="access.signup" href="register.php" class="text-dark _600">Registro</a>
        </div>
      </div>
    </div>
  </div>
  <script src="libs/jquery.js"></script>
  <script src="libs/tether.min.js"></script>
  <script src="libs/bootstrap.js"></script>
  <script src="libs/jquery/underscore/underscore-min.js"></script>
  <script src="libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
  <script src="libs/jquery/PACE/pace.min.js"></script>
  <script src="html/scripts/config.lazyload.js"></script>
  <script src="html/scripts/palette.js"></script>
  <script src="html/scripts/ui-load.js"></script>
  <script src="html/scripts/ui-jp.js"></script>
  <script src="html/scripts/ui-include.js"></script>
  <script src="html/scripts/ui-device.js"></script>
  <script src="html/scripts/ui-form.js"></script>
  <script src="html/scripts/ui-nav.js"></script>
  <script src="html/scripts/ui-screenfull.js"></script>
  <script src="html/scripts/ui-scroll-to.js"></script>
  <script src="html/scripts/ui-toggle-class.js"></script>
  <script src="html/scripts/app.js"></script>
  <script src="libs/jquery/jquery-pjax/jquery.pjax.js"></script>
  <script src="html/scripts/ajax.js"></script>
</body>
</html>
