#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <ArduinoJson.h>
#include <Adafruit_MLX90614.h>


Adafruit_MLX90614 mlx = Adafruit_MLX90614();

#ifndef STASSID
#define STASSID "VIETTEL-1FDF" //id wifi
#define STAPSK  "4097D0C2" // pass wifi
#endif

const char* ssid = STASSID;
const char* password = STAPSK;

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println();
  Serial.print("Ket noi toi: ");
  Serial.println(STASSID);
  mlx.begin();
}

void loop() {
    delay(1000);
  
    double ambientC = mlx.readAmbientTempC();
    double objectC = mlx.readObjectTempC();
    double ambientF = mlx.readAmbientTempF();
 
  
    if (isnan(ambientC) || isnan(objectC)) {
      Serial.println("Failed to read from WEBMOS D1 sensor!");
      return;
    }
    
    String request = "http://192.168.1.115:8100/index.php?ambientC=" + String(ambientC) + "&objectC=" +String(objectC);
    Http_ReQuest(request);
    
    Serial.println();
    Serial.print("DO NHIET DO: ");
    Serial.print("Nhiet do cam bien: ");
    Serial.print(ambientC);
    Serial.print("*C\t");
    Serial.print("Nhiet do nuoc da: ");
    Serial.print(objectC);
    Serial.print("*C");
    Serial.println();
}


void Http_ReQuest(String request){
  if(WiFi.status() == WL_CONNECTED){
    HTTPClient http;
    String url = request;
    http.begin(url);
    int httpCODE = http.GET();
    if(httpCODE > 0){
      String payload = http.getString();
      }
    else{
      Serial.printf("HTTP GET failed, ERRORS: %s\n", http.errorToString(httpCODE).c_str());
      }
      http.end();
    }
  }
