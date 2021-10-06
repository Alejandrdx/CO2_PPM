<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.js.cloudfare.com/ajax/libs/Chart.js/3.5.1/Chart.min.css" rel="stylesheet">
    
    <title>PLATAFORMA IoT PARA LA DETECCIÓN DE NIVELES DE CO2 Y PREVENCIÓN DE LA TRANSMISIÓN DEL COVID EN AMBIENTES CERRADOS</title>
  </head>
  <body>
    <h1>PLATAFORMA IoT PARA LA DETECCIÓN DE NIVELES DE CO2 Y PREVENCIÓN DE LA TRANSMISIÓN DEL COVID EN AMBIENTES CERRADOS</h1>
       <div class="col-lg-15"style="padding: top 20px;">    
            <div class="card">
                <div class="card-header">
                    NIVELES DE CO2
                </div>
                <div class="card-body">
                    <h5 class="card-title">VISUALIZACIÓN DE DATOS TOMADOS POR SENSOR MQ 135 + ESP32</h5>
                    <p class="card-text">En esa sección podemos observar los niveles de CO2 en este ambiente cerrado, lo cual nos ayudará a saber la probabilidad de contagio del SARS COV-2</p>
                    <div class="row">
                        <div class="col-lg-12">
                            <canvas id="myChart2" width="300" height="300"></canvas>
                        </div>
                        
                 
                </div>
            </div>
        </div>
  
    
  </body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
CargarDatosGraficoLineBucle();
var myChart;
function CargarDatosGraficoBar()
{
    $.ajax({
       url:'controlador_grafico.php',
       type:'POST'
    }).done(function(resp){
        var titulo = [];
        var cantidad=[];
        var data = JSON.parse(resp);
        for(var i=0; i<data.length;i++)
        {
            cantidad.push(data[i][3])
            console.log(data[i][3])
            titulo.push(data[i][2])
            console.log(data[i][2])
            
        }
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: titulo,
                datasets: [{
                    label: 'NIVELES DE CO2',
                    data: cantidad,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
    })
   

}
function CargarDatosGraficoLineBucle(){
    for(var i = 0 ; i<50; i++){
        setTimeout(function() {
            if (myChart) {
                myChart.destroy();
            }
            
            CargarDatosGraficoLine()
            
        }, i * 5000);
        
    }
}
function CargarDatosGraficoLine()
{
    $.ajax({
       url:'controlador_grafico.php',
       type:'POST'
    }).done(function(resp){
        var titulo = [];
        var cantidad=[];
        var data = JSON.parse(resp);
        for(var i=0; i<data.length;i++)
        {
            cantidad.push(data[i][3])
            console.log(data[i][3])
            titulo.push(data[i][2])
            console.log(data[i][2])
            
        }
        var ctx = document.getElementById('myChart2');
         myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: titulo,
                datasets: [{
                    label: 'NIVELES DE CO2',
                    data: cantidad,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                animation : false,
                //Boolean - If we want to override with a hard coded scale
                scaleOverride : true,
                //** Required if scaleOverride is true **
                //Number - The number of steps in a hard coded scale
                scaleSteps : 20,
                //Number - The value jump in the hard coded scale
                scaleStepWidth : 10,
                //Number - The scale starting value
                scaleStartValue : 0
            }
        });
        
    })
    
} 

</script>