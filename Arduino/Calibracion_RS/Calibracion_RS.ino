#include <math.h>
#define SENSOR 35
#define DELAY 1000

double adc;
int RL = 20000;
double RS;
double cont = 0.0;
double a = 5.5973021420;
double b = -0.365425824;
double RSF;

void setup() {
  Serial.begin(115200);
}

void loop() {
  for ( int i = 0; i < 301; i++){
    adc = analogRead(SENSOR);
    RS = RL * (1023/adc) - RL;
    Serial.println(RS);
    cont = cont + RS;
    delay(DELAY);
  }

  RSF = cont / 300;
  
  adc = analogRead(SENSOR);
  double R0 = RSF/(a*(pow(414.47, b)));

  Serial.print("RS: ");
  Serial.println(RSF);

  Serial.print("R0: ");
  Serial.println(R0);
}
