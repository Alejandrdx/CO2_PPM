#include <math.h>
#define SENSOR 35
#define DELAY 1000

void setup() {
  Serial.begin(115200);
}

void loop() {
  double adc = analogRead(SENSOR);
  int RL = 20000;
  double R0 = 145428.77;
  double a = 5.5973021420;
  double b = -0.365425824;

  double V = adc * (5.0 / 1023.0);
  double RS = RL * ((5.0 - V) / V);
  double ppm =  pow( ((RS/R0)/a) , 1/b);
  
  Serial.print(" adc: ");
  Serial.print(adc);

  Serial.print(" ppm: ");
  Serial.print(ppm);

  Serial.print(" VOLTAJE: ");
  Serial.print(V);

  Serial.print(" RS: ");
  Serial.println(RS);
  
  delay(DELAY);
}
