<?php
session_start();
$logged = $_SESSION['logged'];

if(!$logged){
  echo "Ingreso no autorizado Prueba 2";
  die();
}

$devices = $_SESSION['devices'];
$state = False;

$conn = mysqli_connect("localhost","admin_co2iot","73896583","admin_co2iot");

if ($conn==false){
  echo "Hubo un problema al conectarse a María DB";
  die();
}

if( isset($_POST['id_to_select']) && $_POST['id_to_select']!="") {
  $id_to_select = $_POST['id_to_select'];
  $result = $conn->query("SELECT * FROM `admin_co2iot`.`device` WHERE  `device_id`=$id_to_select");
  $device_select = $result->fetch_all(MYSQLI_ASSOC);

  $first_date = strip_tags($_POST['first_date']);
  $second_date = strip_tags($_POST['second_date']);
  $result2 = $conn->query("SELECT * FROM data WHERE data_device_id = '$id_to_select' AND data_date BETWEEN '$first_date' AND '$second_date'");
  $select_date = $result2->fetch_all(MYSQLI_ASSOC);

  $state = True;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Dashboard</title>
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
  <link rel="stylesheet" type="text/css" href="libs/bootstrap-datetimepicker.css">
  <style type="text/css">
    .highcharts-figure,
    .highcharts-data-table table {
      min-width: 1000px;
      max-width: 1000px;
      margin: 1em auto;
    }

    .highcharts-data-table table {
      font-family: Verdana, sans-serif;
      border-collapse: collapse;
      border: 1px solid #ebebeb;
      margin: 10px auto;
      text-align: center;
      width: 100%;
      max-width: 500px;
    }

    .highcharts-data-table caption {
      padding: 1em 0;
      font-size: 1.2em;
      color: #555;
    }

    .highcharts-data-table th {
      font-weight: 600;
      padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
      padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
      background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
      background: #f1f7ff;
    }
  </style>
</head>

<body>
  <div class="app" id="app">
    <div id="aside" class="app-aside modal nav-dropdown">
      <div class="left navside black dk" data-layout="column">
        <div class="navbar no-radius danger">
          <a class="navbar-brand">
            <!--<img class="center" src="assets/images/logo.png" alt=".">-->
            <div class="m-b text-sm-center">
              <h2 class=" _700 l-s-n-1x m-b-md text-white">I<span class="text-dark">o</span><span class="text-white">T</span></h2>
            </div>
          </a>
        </div>
        <div class="hide-scroll" data-flex>
          <nav class="scroll nav-light">
            <ul class="nav" ui-nav>
              <li class="nav-header hidden-folded">
                <small class="text-muted">Menu</small>
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
            <div class="col-xs-12 col-sm-4">
              <div class="box p-a">
                <div class="pull-left m-r">
                  <span class="w-48 rounded  warn">
                    <i class="material-icons md-24">domain</i>
                  </span>
                </div>
                <div class="clear">
                  <h4 class="m-0 text-lg _300"><b id="display_alias">-- </b><span class="text-sm"></span></h4>
                  <small class="text-muted">Alias</small>
                </div>
              </div>
            </div>
            <div class="col-xs-6 col-sm-4">
              <div class="box p-a">
                <div class="pull-left m-r">
                  <span class="w-48 rounded info">
                    <i class="material-icons md-24">filter_drama</i>
                  </span>
                </div>
                <div class="clear">
                  <h4 class="m-0 text-lg _300"><b id="display_ppm">-- </b><span class="text-sm"> ppm</span></h4>
                  <small class="text-muted">Concentracion CO2</small>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-4">
              <div id='colorPpm' class="box p-a">
                <div class="pull-left m-r">
                  <span class="w-48 rounded  danger">
                    <i class="material-icons md-24">warning</i>
                  </span>
                </div>
                <div class="clear">
                  <h4 class="m-0 text-lg _300"><b id="display_chipId">-- </b><span class="text-sm"></span></h4>
                  <small id='textPpm'>Peligro de contagio</small>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-6">
              <div class="box p-a">
                <div class="box-header">
                  <h2>Seleccionar Dispositivo</h2>
                  <small>Seleccione un dispositivo y las fechas para mostrar los datos.</small>
                </div>
                <div class="box-divider m-0"></div>
                <div class="box-body">
                  <form class="" method="post">
                    <div class="form-group">
                      <label for="id_to_select">Dispositivos:</label>
                      <select name="id_to_select" class="form-control select2" ui-jp="select2" ui-options="{theme: 'bootstrap'}">
                        <?php foreach ($devices as $device ) { ?>
                          <option value="<?php echo  $device['device_id']?>"><?php echo $device['device_alias']?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="box-divider m-1"></div>
                    <div class="form-group">
                      <label for="first_date">Desde:</label><br>
                      <input type="datetime-local" id="first_date" name="first_date">
                    </div>
                    <div class="form-group">
                      <label for="second_date">Hasta:</label><br>
                      <input type="datetime-local" id="second_date" name="second_date">
                    </div>
                    <div class="box-divider m-1"></div><br>
                    <button type="submit" class="btn btn-fw green">Seleccionar</button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6">
              <div class="box p-a">
                <div class="form-group row">
                  <div id="chart_data_live"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 col-sm-12">
              <div class="box p-a">
                <div class="form-group row">
                  <figure class="highcharts-figure">
                      <div id="chart_data"></div>
                  </figure>
                </div>
              </div>
            </div>
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
  <script src="libs/js/moment/moment.js"></script>
  <script src="libs/bootstrap-datetimepicker.min.js"></script>
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
  <script src="https://code.highcharts.com/stock/highstock.js"></script>
  <script src="https://code.highcharts.com/stock/modules/series-label.js"></script>
  <script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>
  <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
  <script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
  <script type="text/javascript">
    var ppm = 0;

    function update_values(alias, ppm) {
      if(Number(ppm) < 601){
        document.getElementById("colorPpm").style.backgroundColor = '#24E02C';
        $("#display_chipId").html('Bajo');
      }
      if(Number(ppm) < 1001 && Number(ppm) >= 601){
        document.getElementById("colorPpm").style.backgroundColor = '#E0D31F';
        $("#display_chipId").html('Moderado');
      }
      if(Number(ppm) < 1501 && Number(ppm) >= 1001){
        document.getElementById("colorPpm").style.backgroundColor = '#E0432D';
        $("#display_chipId").html('Alto');
      }
      if(Number(ppm) >= 1501){
        document.getElementById("colorPpm").style.backgroundColor = '#E01B0E';
        $("#display_chipId").html('Muy Alto');
      }
      document.getElementById("display_chipId").style.color = 'white';
      document.getElementById("textPpm").style.color = 'white';
      $("#display_alias").html(alias);
      $("#display_ppm").html(ppm);
    }

    function process_msg(topic, message) {
      var msg = message.toString();
      var sp = msg.split(",");
      var chipId = sp[0];
      <?php if ($state) {
        if( $device_select[0] ) { ?>
          var alias = '<?php echo $device_select[0]['device_alias'] ?>';
        <?php } else { ?>
          var alias = '---';
        <?php }}?>
      ppm = sp[1];
      console.log(parseFloat(ppm));
      update_values(alias, ppm);
    }

    const options = {
      connectTimeout: 4000,
      clientId: 'iotmc',
      username: 'web_client',
      password: 'public',

      keepalive: 60,
      clean: true,
    }

    var connected = false;
    const WebSocket_URL = 'wss://www.utpco2iot.ml:8094/mqtt'
    const client = mqtt.connect(WebSocket_URL, options)


    client.on('connect', () => {
      console.log('Mqtt conectado por WS! Exito!')
      <?php if ($state){ ?>
        <?php foreach ($device_select as $device) { ?>
          client.subscribe('<?php echo $device['device_id'] ?>/ppm', { qos: 0 }, (error) => {
            if (!error) {
              console.log('Suscripción exitosa!')
            } else {
              console.log('Suscripción fallida!')
            }
          })
      <?php  }}?>
    })

    client.on('message', (topic, message) => {
      console.log('Mensaje recibido bajo tópico: ', topic, ' -> ', message.toString())
      process_msg(topic, message);
    })

    client.on('reconnect', (error) => {
      console.log('Error al reconectar', error)
    })

    client.on('error', (error) => {
      console.log('Error de conexión:', error)
    })

    Highcharts.setOptions({
      lang: {
        loading: 'Cargando...',
        months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        shortMonths: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
        exportButtonTitle: "Exportar",
        printButtonTitle: "Importar",
        rangeSelectorFrom: "Desde",
        rangeSelectorTo: "Hasta",
        rangeSelectorZoom: "Período",
        downloadPNG: 'Descargar imagen PNG',
        downloadJPEG: 'Descargar imagen JPEG',
        downloadPDF: 'Descargar imagen PDF',
        downloadSVG: 'Descargar imagen SVG',
        downloadCSV: 'Descargar en CSV',
        downloadXLS: 'Descargar en XLS',
        printChart: 'Imprimir',
        resetZoom: 'Reiniciar zoom',
        resetZoomTitle: 'Reiniciar zoom',
        thousandsSep: ",",
        decimalPoint: '.',
        contextButtonTitle: 'Título del botón de contexto',
        exitFullscreen: 'Salir de pantalla completa',
        viewFullscreen: 'Ver en pantalla completa',
        hideData: 'Esconder información en tabla',
        viewData: 'Mostrar información en tabla'
      }
    });

    Highcharts.stockChart('chart_data', {
      time: {
        useUTC: false
      },
      rangeSelector: {
        buttons: [{
          count: 15,
          type: 'minute',
          text: '15Min'
        }, {
          count: 30,
          type: 'minute',
          text: '30Min'
        }, {
          count: 1,
          type: 'hour',
          text: '1Hora'
        }, {
          type: 'all',
          text: 'Todo'
        }],
        selected: 0
      },
      navigator: {
        enabled: false
      },
      title: {
          text: 'Tabla de Datos Seleccionados'
      },
      series: [{
          name: 'PPM',
          <?php if ($state) {?>
          data: (function () {
                  var data_aux2 = []
                  var data_aux = <?php echo json_encode($select_date) ?>;
                  data_aux.forEach(function(data){
                    data_aux2.push([(new Date(data['data_date'])).getTime(), Number(data['data_ppm'])])
                  })
                return data_aux2;
            }()),
          <?php } else { ?>
          data: [],
          <?php } ?>
          tooltip: {
              valueDecimals: 2
          }
      }]
    });
    Highcharts.stockChart('chart_data_live', {
      chart: {
        events: {
          load: function() {
            var series = this.series[0];
            setInterval(function() {
              var x = (new Date()).getTime(),
                y = parseFloat(ppm);
              series.addPoint([x, y], true, true);
            }, 5000);
          }
        }
      },
      time: {
        useUTC: false
      },
      rangeSelector: {
        buttons: [{
          count: 1,
          type: 'minute',
          text: '1 Min'
        }, {
          count: 5,
          type: 'minute',
          text: '5 Min'
        }, {
          type: 'all',
          text: 'Todo'
        }],
        inputEnabled: false,
        selected: 0
      },
      title: {
        text: 'Concentracion C02 en tiempo real'
      },
      exporting: {
        enabled: false
      },
      series: [{
        name: 'PPM',
        data: (function () {
              var data = [],
                  time = (new Date()).getTime(),
                  i;

              for (i = -720; i <= 0; i += 1) {
                  data.push([
                      time + i * 1000, 0
                  ]);
              }
              return data;
          }())
      }]
    });

  </script>
</body>

</html>
