#define TRIG_PIN 23 // ESP32 pin GIOP15 connected to Ultrasonic Sensor's TRIG pin
#define ECHO_PIN 19 // ESP32 pin GIOP4 connected to Ultrasonic Sensor's ECHO pin
#define SERVO_PIN 18 // ESP32 pin GIOP18 connected to Servo 
#define DHT_PIN 14 // ESP32 pin GIOP14 connected to DHT22
#define PENGHANGAT_PIN 17 // ESP32 pin GIOP12 connected to LED as Penghangat
#define BUZZER_PIN 16 // ESP32 pin GIOP12 connected to BUZZER

// library
#include <WiFi.h>
#include <WiFiClient.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include <ESP32Servo.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#include <LiquidCrystal_I2C.h>

// permainan api
String payload = "";
String host = "https://aikan-main.desagerdu.my.id";
char* PASSWORD = "DENIMANUSIAIKAN";
char* WIFI = "ISI SENDIRI";
char* WIFI_PASSWORD = "ISI SENDIRI";

// bagian masing2 data control yang di dapat dari json
String penghangat_akuarium = "", status_buzzer = "", status_servo = "", status_pemberian_pakan = "", presentase_pakan = "";
  
// bagian pakan
float duration_us, distance_cm, durasi_pakan = 1000;

// bagian suhu
float suhu;

// set the LCD number of columns and rows
int lcdColumns = 16;
int lcdRows = 2;

// deklarasi variabel untuk json
StaticJsonDocument<1024> doc;

// library
Servo servo;
OneWire oneWire(DHT_PIN);
DallasTemperature sensorSuhu(&oneWire);
HTTPClient http;
LiquidCrystal_I2C lcd(0x27, lcdColumns, lcdRows); 

void setup() {
  // begin serial port
  Serial.begin (9600);

  // sensor suhu
  sensorSuhu.begin();
  // sensor ultrasonic
  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);
  // servo
  servo.attach(SERVO_PIN, 500, 2400);
  // buzer
  pinMode(BUZZER_PIN, OUTPUT);
  // konfigurasi penghangat akuarium
  pinMode(PENGHANGAT_PIN, OUTPUT);
  // initialize LCD
  lcd.init();                 
  lcd.backlight();
  //  inisialisasi penghangat akuarium
  digitalWrite(PENGHANGAT_PIN, LOW);

  // cek koneksi wifi
  lcd.setCursor(0, 0);
  Serial.print("Connecting to WiFi");
  lcd.print("WIFI Disconnect!");
  WiFi.begin(WIFI, WIFI_PASSWORD, 6);
  while (WiFi.status() != WL_CONNECTED) {
    delay(100);
    Serial.println("WIFI Disconnect!");
  }
  Serial.println("WIFI Connect!");
  lcd.clear();
  lcd.print("WIFI Connect!");
}

void loop() {
  lcd.clear(); 
  if (WiFi.status() == WL_CONNECTED) {
    // JIKA AMBIL DATA TIDAK ERROR
    if (ambilData("/API/ambil_data_kontrol/") == 200) {
      dataControl();
      aksiControl();

      cekPakan();
      cekSuhu();
      
      // UNTUK MENGETEST
      // Serial.println("Delay 5 detik");
      // delay(5000);

      kirimDataSensor();
    } else {
      lcd.clear();
      lcd.setCursor(0, 0);
      // print message
      lcd.print("Error data");
    }
  } else {
    lcd.setCursor(0, 0);
    lcd.clear();
    lcd.print("WIFI Disconnect!");
  }
  delay(10000);
}

// menjalankan aksi output
void dataControl() {
    deserializeJson(doc, payload);
    penghangat_akuarium = doc["penghangat_akuarium"].as<String>();
    status_servo = doc["servo"].as<String>();
    status_buzzer = doc["buzzer"].as<String>();
    status_pemberian_pakan = doc["status_pemberian_pakan"].as<String>();
    durasi_pakan = ((float) doc["durasi_pakan"] * 1000);
    presentase_pakan = doc["presentase_pakan"].as<String>();
}

void aksiControl(){
    // BUZZER
    //  Jika buzzer tidak dimatikan paksa
    if (status_buzzer == "ON") {
        digitalWrite(BUZZER_PIN, HIGH);
    } else {
        digitalWrite(BUZZER_PIN, LOW);
    }

    // Penghangat akuarium
    if (penghangat_akuarium == "ON") {
      digitalWrite(PENGHANGAT_PIN, HIGH);
    } else {
      digitalWrite(PENGHANGAT_PIN, LOW);
    }

    // SERVO
    if (status_servo == "ON") {
      int i = 0;
      for (i = 170; i >= 0; i--){
        servo.write(i);
        delay(5);
      }
      delay(durasi_pakan);
      for (i = 0; i <= 170; i++){
        servo.write(i);
        delay(5);
      }
      
      status_pemberian_pakan = "Pemberian pakan berhasil";
    }
}

// mengecek pakan
void cekPakan() {
  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);;
  digitalWrite(TRIG_PIN, LOW);

  duration_us = pulseIn(ECHO_PIN, HIGH);
  distance_cm = 0.017 * duration_us;
  Serial.println("Jarak : " + (String) distance_cm);
  lcd.setCursor(0, 0);
  // print message
  lcd.print("Persentase: " + (String) presentase_pakan + "%");
}

void cekSuhu() {
  sensorSuhu.requestTemperatures();
  suhu = sensorSuhu.getTempCByIndex(0);
  Serial.println("Temp: " + String(suhu, 2) + "°C");
  lcd.setCursor(0, 1);
  // print message
  lcd.print("Suhu: " + String(suhu, 2) + " C");
}

void kirimDataSensor(){
  String url = "/API/tambah_data/";
  String postData = "sensor_ultrasonik=";
  postData += distance_cm;
  postData += "&sensor_suhu="; 
  postData += suhu;
  postData += "&status_pemberian_pakan="; 
  postData += status_pemberian_pakan;
  postData += "&status_servo="; 
  postData += status_servo;
  kirimData(url, postData);
}

// AMBIL DATA 
int ambilData(String url){
  http.begin(host + url + PASSWORD);
  http.addHeader("Content-Type", "application/json");
  int httpResponseCode = http.GET();
  Serial.println(httpResponseCode);
  if (httpResponseCode == 200) {
    payload = http.getString();
  } else {
    payload = "";
  }
  http.end();
  return httpResponseCode;
}

// MENGIRIM DATA KE DATABASE
int kirimData(String url, String getPost){
  http.begin(host + url + PASSWORD);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpResponseCode = http.POST(getPost);
  Serial.println((String)httpResponseCode);
//  Serial.println((String)http.getString());
  http.end();
  return httpResponseCode;
}
