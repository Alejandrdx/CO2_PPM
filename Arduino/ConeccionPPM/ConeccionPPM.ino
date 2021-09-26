#include <HTTPClient.h>
#include <WiFi.h>
#include <math.h>

#define SENSOR 35
#define DELAY 1000

const char* ssid = "red1";
const char* password =  "lourdesyuo";

char host[48];
//String url = "http://192.168.1.11/ppm.php";
String url = "https://ferric-sale.000webhostapp.com/ppm.php";
uint32_t chipId = 0;

void sendData(String data){
  HTTPClient http;
  
  http.begin(url);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  int responseCode = http.POST(data);

  if(responseCode>0){
      Serial.println("Código HTTP ► " + String(responseCode));   //Print return code

      if(responseCode == 200){
        String response = http.getString();
        Serial.println("El servidor respondió ▼ ");
        Serial.println(response);
      }
      
  }else{
    Serial.print("Error enviando POST, código: ");
    Serial.println(responseCode);
  }

    http.end();
}

void setup() {
  Serial.begin(115200);
  Serial.println("");

  for(int i=0; i<17; i=i+8) {
    chipId |= ((ESP.getEfuseMac() >> (40 - i)) & 0xff) << i;
  }

  Serial.printf("ESP32 Chip model = %s Rev %d\n", ESP.getChipModel(), ESP.getChipRevision());
  Serial.printf("This chip has %d cores\n", ESP.getChipCores());
  Serial.print("Chip ID: "); Serial.println(chipId);
  
  WiFi.begin(ssid, password);

  Serial.print("Conectando...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.print("Conectado con éxito, mi IP es: ");
  Serial.println(WiFi.localIP());
}

void loop() {
  double adc = analogRead(SENSOR);
  int RL = 20000;
  double R0 = 193720.78;
  double a = 5.5973021420;
  double b = -0.365425824;

  double V = adc * (5.0 / 1023.0);
  double RS = RL * (5.0 - V) / V;
  double ppm =  pow( ((RS/R0)/a) , 1/b);
  
  Serial.print(" ppm: ");
  Serial.println(ppm);
  
  sendData("chipId=" + String(chipId) + "&ppm=" + String(ppm));

  delay(DELAY);
}
