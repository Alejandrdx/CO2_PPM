var mysql = require('mysql')
var mqtt = require('mqtt')

var con = mysql.createConnection({
  host: 'utpco2iot.ml',
  user: 'admin_co2iot',
  password: '73896583',
  database: 'admin_co2iot'
})

var options = {
  port: 1883,
  host: 'utpco2iot.ml',
  clientId: 'access_control_server_' + Math.round(Math.random() * (0 - 10000) * -1),
  username: 'web_client',
  password: 'public',
  keepalive: 60,
  reconnectPeriod: 1000,
  protocolId: 'MQIsdp',
  protocolVersion: 3,
  clean: true,
  encoding: 'utf8'
}

var client = mqtt.connect('mqtt://utpco2iot.ml', options)

client.on('connect', function(){
  client.subscribe('+/#', function(err) {
    console.log('Subscripcion exitosa')
  })
})

client.on('message', function(topic, message) {
  console.log('Mensaje recibido de : ' + topic + ' Mensaje: ' + message.toString())

  var message = message.toString()
  var message_aux = message.split(',')

  var chipId = message_aux[0]
  var ppm = message_aux[1]

  if (chipId && ppm){
    var query = `INSERT INTO data (data_ppm, data_device_id) VALUES (?, ?);`
    con.query(query, [ppm, chipId], function(error, result, fields){
      if (error) throw error
      console.log("Insercion en base de datos realizada con exito!")
    })
  }
})

con.connect(function(error) {
  if (error) throw error

  var query = 'SELECT * FROM user WHERE 1'
  con.query(query, function(error, result, fields) {
    if (error) throw error
    if (result.length > 0) {
      console.log(result)
    }
  })
})

setInterval(function() {
  var query = 'SELECT 1 + 1 as result'
  con.query(query, function(error, result, fields) {
    if (error) throw error
  })
}, 5000)
