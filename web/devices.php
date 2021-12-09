<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo "Ingreso no autorizado";
  die();
}

$alias="";
$serie="";
$user_id = $_SESSION['user_id'];

$conn = mysqli_connect("localhost","admin_co2iot","73896583","admin_co2iot");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

if( isset($_POST['id_to_delete']) && $_POST['id_to_delete']!="") {
  $id_to_delete = $_POST['id_to_delete'];
  $conn->query("DELETE FROM `admin_co2iot`.`device` WHERE  `device_id`=$id_to_delete");
}

if( isset($_POST['series']) && isset($_POST['alias'])) {
  $alias = strip_tags($_POST['alias']);
  $series = strip_tags($_POST['series']);
  $conn->query("INSERT INTO `device` (`device_id`, `device_alias`, `device_user_id`) VALUES ('".$series."', '".$alias."', '".$user_id."');");
}

$result = $conn->query("SELECT * FROM `device` WHERE `device_user_id` = '".$user_id."'");
$devices = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Dispositvos</title>
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
</head>

<body>
  <div class="app" id="app">
    <div id="aside" class="app-aside modal nav-dropdown">
      <div class="left navside black dk" data-layout="column">
        <div class="navbar no-radius danger">
          <a class="navbar-brand">
            <div class="m-b text-sm-center">
              <h2 class=" _700 l-s-n-1x m-b-md text-white">I<span class="text-dark">o</span><span class="text-white">T</span></h2>
            </div>
          </a>
        </div>
        <div class="hide-scroll" data-flex>
          <nav class="scroll nav-light">
            <ul class="nav" ui-nav>
              <li class="nav-header hidden-folded">
                <small class="text-muted">Main</small>
              </li>
              <li>
                <a href="dashboard.php">
                  <span class="nav-icon">
                    <i class="fa fa-building-o"></i>
                  </span>
                  <span class="nav-text">Principal</span>
                </a>
              </li>
              <li>
                <a href="devices.php">
                  <span class="nav-icon">
                    <i class="fa fa-cogs"></i>
                  </span>
                  <span class="nav-text">Dispositivos</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
    <div id="content" class="app-content box-shadow-z0" role="main">
      <div ui-view class="app-body" id="view">
        <div class="padding">
          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header">
                  <h2>Agregar Dispositivo</h2>
                  <small>Ingresa el nombre (alias) y el número de serie del dispositivo que quieres instalar.</small>
                </div>
                <div class="box-divider m-0"></div>
                <div class="box-body">
                  <form role="form" method="post" target="">
                    <div class="form-group">
                      <label for="alias">Alias</label>
                      <input name="alias" type="text" class="form-control" placeholder="Ej: Casa Campo">
                    </div>
                    <div class="form-group">
                      <label for="series">Serie</label>
                      <input name="series" type="text" class="form-control" placeholder="Ej: 777222">
                    </div>
                    <button type="submit" class="btn white m-b">Registrar</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="box">
                <div class="box-header">
                  <h2>Dispositivos</h2>
                </div>
                <table class="table table-striped b-t">
                  <thead>
                    <tr>
                      <th>Alias</th>
                      <th>Fecha</th>
                      <th>Serie</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($devices as $device) {?>
                    <tr>
                      <td><?php echo $device['device_alias'] ?></td>
                      <td><?php echo $device['device_date'] ?></td>
                      <td><?php echo $device['device_id'] ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <h5>Eliminar Dispositvos</h5>
            <form class="" method="post">
              <div class="form-group">
                <select name="id_to_delete" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                  <?php foreach ($devices as $device ) { ?>
                  <option value="<?php echo  $device['device_id']?>"><?php echo $device['device_alias']?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="submit" class="btn btn-fw danger">Eliminar</button>
            </form>
          </div>
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
