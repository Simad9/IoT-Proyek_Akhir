#include <Arduino.h>
#include <Wire.h>
#include <WiFi.h>
#include <WiFiClient.h>
#include <HTTPClient.h>
#include <LiquidCrystal_I2C.h>

#include <SPI.h>
#include <MFRC522.h>
#include <ESP32Servo.h>

//Deklarasi RFID
#define RFID_RST 4

//Variable Global
int parkiranTersedia = 3;

//Wifi
const char *ssid = "iPhone";
const char *pass = "1sampai8";
const char *host = "192.168.1.88";

//Deklarasi Umum Masuk
String lastRFIDMasuk = "";
bool isGateMasukOpen = false;
int infraredMasukStatus = 0;

//Deklarasi Umum Keluar
String lastRFIDKeluar = "";
bool isGateKeluarOpen = false;
int infraredKeluarStatus = 0;

// Deklarasi untuk RFID Masuk
#define RFID_MASUK_SDA 2
MFRC522 RFID_MASUK;

// Deklarasi Untuk Infrared Masuk
#define INFRARED_MASUK 22

// Deklarasi Untuk Servo Masuk
#define SERVO_MASUK 23
Servo pintuMasukServo;

//Deklarasi Untuk LCD Masuk
LiquidCrystal_I2C lcdMasuk(0x23, 16, 2);

// Deklarasi untuk RFID Keluar
#define RFID_KELUAR_SDA 14
MFRC522 RFID_KELUAR;

// Deklarasi Untuk Infrared Keluar
#define INFRARED_KELUAR 26

// Deklarasi Untuk Servo Keluar
#define SERVO_KELUAR 25
Servo pintuKeluarServo;

//Deklarasi Untuk LCD Keluar
#define LCD_KELUAR_SDA 21
#define LCD_KELUAR_SCL 27
LiquidCrystal_I2C lcdKeluar(0x27, 16, 2);

void connectWifi()
{
  Serial.print("Connecting to WiFi");
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, pass);


  while (WiFi.status() != WL_CONNECTED)
  {
    Serial.print("WiFi status: ");
    Serial.println(WiFi.status());
    delay(1000);
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
  Serial.println();
  delay(2000);
}

void sendMasukID(String id)
{
  if (WiFi.status() == WL_CONNECTED)
  {
    WiFiClient client;
    HTTPClient http;

    String address = "http://192.168.1.88/+project/iot-parkir/Front%20End/logic/kirimMasuk.php?id_kartu=";
    address += id;

    http.begin(client, address);
    int httpCode = http.GET();
    String payload;

    if (httpCode > 0)
    {
      payload = http.getString();
      payload.trim();
      if (payload.length() > 0)
      {
        Serial.println(payload + "\n");
      }
    }

    http.end();
  }
  else
  {
    Serial.print("Not connected to wifi ");
    Serial.println(ssid);
    connectWifi();
  }
}

void sendKeluarID(String id)
{
  if (WiFi.status() == WL_CONNECTED)
  {
    WiFiClient client;
    HTTPClient http;

    String address = "http://192.168.1.88/+project/iot-parkir/Front%20End/logic/kirimKeluar.php?id_kartu=";
    address += id;

    http.begin(client, address);
    int httpCode = http.GET();
    String payload;

    if (httpCode > 0)
    {
      payload = http.getString();
      payload.trim();
      if (payload.length() > 0)
      {
        Serial.println(payload + "\n");
      }
    }

    http.end();
  }
  else
  {
    Serial.print("Not connected to wifi ");
    Serial.println(ssid);
    connectWifi();
  }
}

void taskPintuMasuk(void *pvParameters){
  while(true){
    if(!isGateMasukOpen){
      if(parkiranTersedia > 0){
        if(RFID_MASUK.PICC_IsNewCardPresent()){
          if(RFID_MASUK.PICC_ReadCardSerial()){
            lastRFIDMasuk = "";
            for (byte i = 0; i < RFID_MASUK.uid.size; i++) {
              lastRFIDMasuk += String(RFID_MASUK.uid.uidByte[i], HEX);
            }
            Serial.println("Kartu Masuk baru terdeteksi!");
            Serial.print("UID Baru: ");
            Serial.println(lastRFIDMasuk);
            pintuMasukServo.write(0);
            isGateMasukOpen = true;
            RFID_MASUK.PICC_HaltA();
          }
        }
      }
    }
    vTaskDelay(1000 / portTICK_PERIOD_MS);
  }
}

void taskMobilLewatPintuMasuk(void *pvParameters){
  while(true){
    Serial.print("Cek Gate Masuk: ");
    Serial.println(isGateMasukOpen);
    if(isGateMasukOpen){
      Serial.print("Cek Status Masuk: ");
      Serial.println(infraredMasukStatus);
      if(infraredMasukStatus == 0){
        if(digitalRead(INFRARED_MASUK) == LOW){
          infraredMasukStatus = 1;
        }
      }else if(infraredMasukStatus == 1){
        if(digitalRead(INFRARED_MASUK) == HIGH){
          infraredMasukStatus = 0;
          isGateMasukOpen = false;
          pintuMasukServo.write(60);
          parkiranTersedia -= 1;
          // lcdMasuk.clear();
          // lcdMasuk.setCursor(0, 0);
          // lcdMasuk.print("Sisa Slot :");
          // lcdMasuk.setCursor(0, 1);
          // lcdMasuk.print(parkiranTersedia);
        }
      }
    }
    vTaskDelay(1000 / portTICK_PERIOD_MS);
  }
}

void taskPintuKeluar(void *pvParameters){
  while(true){
    if(!isGateKeluarOpen){
      if(RFID_KELUAR.PICC_IsNewCardPresent()){
        if(RFID_KELUAR.PICC_ReadCardSerial()){
          lastRFIDKeluar = "";
          for (byte i = 0; i < RFID_KELUAR.uid.size; i++) {
            lastRFIDKeluar += String(RFID_KELUAR.uid.uidByte[i], HEX);
          }

          Serial.println("Kartu Keluar baru terdeteksi!");
          Serial.print("UID Baru: ");
          Serial.println(lastRFIDKeluar);
          isGateKeluarOpen = true;
          pintuKeluarServo.write(120);
          //sendKeluarID(lastRFIDKeluar);
          RFID_KELUAR.PICC_HaltA();
        }
      }
    }
    vTaskDelay(1000 / portTICK_PERIOD_MS);
  }
}

void taskMobilLewatPintuKeluar(void *pvParameters){
  while(true){
    Serial.print("Cek Gate Keluar: ");
    Serial.println(isGateKeluarOpen);
    if(isGateKeluarOpen){
      Serial.print("Cek Status Keluar: ");
      Serial.println(infraredKeluarStatus);
      if(infraredKeluarStatus == 0){
        if(digitalRead(INFRARED_KELUAR) == LOW){
          infraredKeluarStatus = 1;
        }
      }else if(infraredKeluarStatus == 1){
        if(digitalRead(INFRARED_KELUAR) == HIGH){
          if(parkiranTersedia < 3){
            parkiranTersedia += 1;
          }
          infraredKeluarStatus = 0;
          isGateKeluarOpen = false;
          pintuKeluarServo.write(0);
        }
      }
    }
    vTaskDelay(1000 / portTICK_PERIOD_MS);
  }
}

void setup(){
  Serial.begin(115200);
  
  // WiFi.mode(WIFI_STA);
  // WiFi.begin(ssid, pass);

  // while (WiFi.status() != WL_CONNECTED)
  // {
  //   Serial.print("WiFi status: ");
  //   Serial.println(WiFi.status());
  //   delay(1000);
  // }

  //Inisialisasi RFID
  SPI.begin(5, 19, 18, 4); // SCK, MISO, MOSI
  RFID_MASUK.PCD_Init(RFID_MASUK_SDA, RFID_RST);   // Inisialisasi RFID Masuk
  RFID_KELUAR.PCD_Init(RFID_KELUAR_SDA, RFID_RST);  // Inisialisasi RFID Keluar

  // Inisialisasi LCD
  Wire.begin(LCD_KELUAR_SDA, LCD_KELUAR_SCL);
  lcdMasuk.begin();
  lcdMasuk.backlight();
  lcdMasuk.clear();
  lcdMasuk.setCursor(0, 0);
  lcdMasuk.print("Sisa Slot :");
  lcdMasuk.setCursor(0, 1);  // Set cursor di baris kedua
  lcdMasuk.print(parkiranTersedia);
  delay(2000);
  lcdMasuk.clear();

  lcdKeluar.begin();
  lcdKeluar.backlight();
  lcdKeluar.clear();
  lcdKeluar.setCursor(0, 0);
  lcdKeluar.print("Sisa Slot :");
  lcdKeluar.setCursor(0, 1);
  lcdKeluar.print(parkiranTersedia);
  delay(1000);
  lcdKeluar.clear();

  //Inisialisasi Infrared
  pinMode(INFRARED_MASUK, INPUT);
  pinMode(INFRARED_KELUAR, INPUT);

  //Inisialisasi Servo
  pintuMasukServo.attach(SERVO_MASUK);
  pintuKeluarServo.attach(SERVO_KELUAR);

  // Membuat task Pintu Masuk
  xTaskCreate(taskPintuMasuk, "Task Pintu Masuk", 4096, NULL, 1, NULL);
  xTaskCreate(taskMobilLewatPintuMasuk, "Task Mobil Lewat Pintu Masuk", 4096, NULL, 1, NULL);

  xTaskCreate(taskPintuKeluar, "Task Pintu Keluar", 4096, NULL, 1, NULL);
  xTaskCreate(taskMobilLewatPintuKeluar, "Task Mobil Lewat Pintu Keluar", 4096, NULL, 1, NULL);
}

void loop(){
  if(isGateMasukOpen){
    lcdMasuk.clear();
    lcdMasuk.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
    lcdMasuk.print("Masuk :");
    lcdMasuk.setCursor(0, 1);  // Set cursor di baris kedua
    lcdMasuk.print(lastRFIDMasuk);
  }else{
    lcdMasuk.clear();
    if(parkiranTersedia == 0){
      lcdMasuk.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
      lcdMasuk.print("Maaf");

      lcdMasuk.setCursor(0, 1);  // Set cursor di baris kedua
      lcdMasuk.print("Parkiran Penuh");
    }else{
      lcdMasuk.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
      lcdMasuk.print("Sisa Slot :");

      lcdMasuk.setCursor(0, 1);  // Set cursor di baris kedua
      lcdMasuk.print(parkiranTersedia);
    }
  }

  if(isGateKeluarOpen){
    lcdKeluar.clear();
    lcdKeluar.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
    lcdKeluar.print("Keluar :");
    lcdKeluar.setCursor(0, 1);  // Set cursor di baris kedua
    lcdKeluar.print(lastRFIDKeluar);
  }else{
    lcdKeluar.clear();
    lcdKeluar.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
    lcdKeluar.print("Sisa Slot :");

    lcdKeluar.setCursor(0, 1);  // Set cursor di baris kedua
    lcdKeluar.print(parkiranTersedia);
  }

  delay(1000);
}