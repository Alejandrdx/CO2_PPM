<?php
  $conn = mysqli_connect("localhost","admin_co2iot","73896583","admin_co2iot");

  if ($conn==false){
    echo "Hubo un problema al conectarse a María DB";
    die();
  }

  $email = "";
  $password = "";
  $password_r = "";
  $msg = "";

  if( isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_r'])) {
    $email = strip_tags($_POST['email']);
    $password = strip_tags($_POST['password']);
    $password_r = strip_tags($_POST['password_r']);

    if ($password==$password_r){
      $result = $conn->query("SELECT * FROM `user` WHERE `user_email` = '".$email."' ");
      $users = $result->fetch_all(MYSQLI_ASSOC);
      $count = count($users);
      if ($count == 0){
        $password = sha1($password);
        $conn->query("INSERT INTO `user` (`user_email`, `user_password`) VALUES ('".$email."', '".$password."');");
        $msg.="Usuario creado correctamente, ingrese haciendo  <a href='login.php'>clic aquí</a> <br>";
      }else{
        $msg.="El mail ingresado ya existe <br>";
      }
    }else{
      $msg = "Las claves no coinciden";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>IoT Masterclass</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
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
          <h2 class=" _700 l-s-n-1x m-b-md text-dark">REGI<span class="text-danger">STRO</span></h2>
        </div>
        <form method="post" target="register.php" name="form">
          <div class="md-form-group">
            <input name="email" type="email" class="md-input" value="<?php echo $email; ?>" required>
            <label>Email</label>
          </div>
          <div class="md-form-group">
            <input name="password" type="password" class="md-input" required>
            <label>Password</label>
          </div>
          <div class="md-form-group">
            <input name="password_r" type="password" class="md-input" required>
            <label>Repetir Password</label>
          </div>
          <button type="submit" class="btn dark btn-block p-x-md">Registrarse</button>
        </form><br>
        <div class="m-b text-sm-center">
          <span class="text-danger">
            <?php echo $msg ?>
          </span>
        </div>
        <div class="p-v-lg text-center">
          <div><a ui-sref="access.signin" href="index.php" class="text-dark _600">Login</a></div>
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
