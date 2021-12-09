#include <Arduino.h>
#include <PubSubClient.h>
#include <WiFi.h>

#define SENSOR 35

//Conexion Wifi
const char * ssid = "F";
const char * password = "pinponsimbadonky";

//Conexion Mqtt
const char * mqtt_server = "utpco2iot.ml";
const int mqtt_port = 1883;
const char * mqtt_user = "web_client";
const char * mqtt_pass = "public";

//WiFiClient
WiFiClient espClient;
PubSubClient client(espClient);

//Variables
long lastMsg = 0;
char msg[50];
uint32_t chipId = 0;
String user_name = "";

//Funciones
void setup_wifi();
void callback(char* topic, byte* payload, unsigned int length);
void reconnect();

void setup() {
  pinMode(2, OUTPUT);
  Serial.begin(115200);

  for(int i=0; i<17; i=i+8) {
    chipId |= ((ESP.getEfuseMac() >> (40 - i)) & 0xff) << i;
  }

  Serial.printf("ESP32 Chip model = %s Rev %d\n", ESP.getChipModel(), ESP.getChipRevision());
  Serial.printf("This chip has %d cores\n", ESP.getChipCores());
  Serial.print("Chip ID: "); Serial.println(chipId);

  randomSeed(micros());
  setup_wifi();
  client.setServer(mqtt_server, mqtt_port);
  client.setCallback(callback);
}

void loop() {
  if (!client.connected()) {
    reconnect();
  }

  client.loop();

  long now = millis();
  if (now - lastMsg > 5000) {
    lastMsg = now;

    double adc = analogRead(SENSOR);
    int RL = 20000;
    double R0 = 104651.35;
    double a = 5.5973021420;
    double b = -0.365425824;

    double V = adc * (5.0 / 1023.0);
    double RS = RL * (5.0 - V) / V;
    double ppm =  pow( ((RS/R0)/a) , 1/b);

    Serial.print(" ppm: ");
    Serial.println(ppm);


    String to_send = String(chipId) + "," + String(ppm);
    to_send.toCharArray(msg, 50);
    Serial.print("Enviando mensaje ");
    Serial.println(msg);

    char topic[25];
    String topic_aux = String(chipId) + "/ppm";
    topic_aux.toCharArray(topic, 25);

    client.publish(topic, msg);
  }
}

void setup_wifi(){
  delay(10);
  Serial.println();
  Serial.print("Conectando a ");
  Serial.println(ssid);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("Conectando a red WiFi");
  Serial.println("Direccion IP: ");
  Serial.println(WiFi.localIP());
}

void callback(char* topic, byte* payload, unsigned int length){
  String incoming = "";
  Serial.print("Mensaje recibido desde -> ");
  Serial.println(topic);
  for (int i = 0; i < length; i++) {
    incoming += (char)payload[i];
  }
  incoming.trim();
  Serial.println("Mensaje -> " + incoming);

  String str_topic(topic);

  if (str_topic == String(chipId) + "/user_name") {
    user_name = incoming;
  }
}

void reconnect(){
  while (!client.connected()) {
    Serial.print("Intentado conexion Mqtt...");
    String clientid = "esp32_";
    clientid += String(random(0xffff), HEX);
    if (client.connect(clientid.c_str(), mqtt_user, mqtt_pass)){
      Serial.println("Conectado!");

      char topic[25];
      String topic_aux = String(chipId) + "/user_name";
      topic_aux.toCharArray(topic, 25);
      client.subscribe(topic);
    } else {
      Serial.print("Fallo de conexion: ");
      Serial.println(client.state());
      Serial.println("Nuevo intento de conexion en 5 segundos");

      delay(5000);
    }
  }
}
