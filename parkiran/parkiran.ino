#include <Arduino.h>
#include <LiquidCrystal_I2C.h>
#include <MFRC522.h>
#include <SPI.h>
#include <ESP32Servo.h>
#include <WiFi.h>         // Library untuk koneksi WiFi
#include <HTTPClient.h>   // Library untuk HTTP request
#include <ArduinoJson.h>

//Deklarasi Ultrasonik 
const int trig1Pin = 23;
const int echo1Pin = 39;
const int trig2Pin = 5;
const int echo2Pin = 18;
const int trig3Pin = 12;
const int echo3Pin = 13;
#define SOUND_SPEED 0.034
#define CM_TO_INCH 0.393701
long duration;
float distanceCm;
float distanceInch;

//Deklarasi Infrared
const int irMasukPin = 34;
const int irKeluarPin = 25;

//Deklarasi Servo
const int servoMasukPin = 32;
const int servoKeluarPin = 33;
Servo servoMasuk;
Servo servoKeluar;

//Deklarasi LCD
const int sdaLCDMasukPin = 21;
const int sclLCDMasukPin = 19;
const int sdaLCDKeluarPin = 4;
const int sclLCDKeluarPin = 16;
LiquidCrystal_I2C lcd1(0x27, 16, 2); // LCD pertama, alamat 0x27
LiquidCrystal_I2C lcd2(0x3F, 16, 2); // LCD kedua, alamat 0x3F

//Deklarasi RFID
const int sdaRFIDMasukPin = 36;
const int sckRFIDMasukPin = 17;
const int misoRFIDMasukPin = 14;
const int mosiRFIDMasukPin = 26;
const int rstRFIDMasukPin = 8;
const int sdaRFIDKeluarPin = 2;
const int sckRFIDKeluarPin = 22;
const int misoRFIDKeluarPin = 27;
const int mosiRFIDKeluarPin = 15;
const int rstRFIDKeluarPin = 11;
MFRC522::MIFARE_Key key;
MFRC522::StatusCode status;
MFRC522 mfrc522Masuk(sdaRFIDMasukPin, rstRFIDMasukPin); 
MFRC522 mfrc522Keluar(sdaRFIDKeluarPin, rstRFIDKeluarPin);

//Informasi WiFi
const char *ssid = "Lab Jaringan";
const char *pass = "ifjaringan";
const char *host = "192.168.66.77";

//Support Var
#define MAX_PARKIR 3
int availParkir = 3;
bool isPass = false;

void setup() {
  Serial.begin(115200);

  //Connect Wifi
  //connectWifi();

  //Setup Ultrasonik
  pinMode(trig1Pin, OUTPUT);
  pinMode(echo1Pin, INPUT);
  pinMode(trig2Pin, OUTPUT);
  pinMode(echo2Pin, INPUT);
  pinMode(trig3Pin, OUTPUT);
  pinMode(echo3Pin, INPUT);

  //Setup LCD
  lcd1.begin();
  lcd1.clear();
  lcd2.begin();
  lcd2.clear();

  //Setup RFID
  SPI.begin();
  mfrc522Masuk.PCD_Init();
  mfrc522Keluar.PCD_Init();

  //Setup Infrared
  pinMode(irMasukPin, INPUT);
  pinMode(irKeluarPin, INPUT);

  //Setup Servo
  servoMasuk.attach(servoMasukPin);
  servoKeluar.attach(servoKeluarPin);
  
  xTaskCreate(objectMasuk, "Object_Masuk", 1024, NULL, 1, NULL);
  xTaskCreate(objectKeluar, "Object_Keluar", 1024, NULL, 1, NULL);
}

void loop() {
  delay(1000);
}

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

void objectMasuk(void *pvParameters){
  if (!mfrc522Masuk.PICC_IsNewCardPresent()) {
    return; // Tidak ada kartu baru
  }

  if (!mfrc522Masuk.PICC_ReadCardSerial()) {
    return; // Gagal membaca kartu
  }

  Serial.print("UID tag yang dibaca: ");
  String tagID = "";
  for (byte i = 0; i < mfrc522Masuk.uid.size; i++) {
    tagID += String(mfrc522Masuk.uid.uidByte[i], HEX);
  }
  tagID.toUpperCase();
  Serial.println(tagID);

  openEntryGate();
  while (digitalRead(irMasukPin) == LOW) {
    delay(10);
  }
  while (digitalRead(irMasukPin) == HIGH) {
    delay(10);
  }
  closeEntryGate();
  mfrc522Masuk.PICC_HaltA(); // Berhentikan pembacaan tag
  mfrc522Masuk.PCD_StopCrypto1(); // Hentikan enkripsi

  availParkir--;
}

// Fungsi untuk membuka palang (servo)
void openEntryGate() {
  servoMasuk.write(90);
}

// Fungsi untuk menutup palang (servo)
void closeEntryGate() {
  servoMasuk.write(0);
}

void objectKeluar(void *pvParameters){
  if (!mfrc522Keluar.PICC_IsNewCardPresent()) {
    return; // Tidak ada kartu baru
  }

  if (!mfrc522Keluar.PICC_ReadCardSerial()) {
    return; // Gagal membaca kartu
  }

  Serial.print("UID tag yang dibaca: ");
  String tagID = "";
  for (byte i = 0; i < mfrc522Keluar.uid.size; i++) {
    tagID += String(mfrc522Keluar.uid.uidByte[i], HEX);
  }
  tagID.toUpperCase();
  Serial.println(tagID);

  openExitGate();
  while (digitalRead(irKeluarPin) == LOW) {
    delay(10);
  }
  while (digitalRead(irKeluarPin) == HIGH) {
    delay(10);
  }
  closeExitGate();
  mfrc522Keluar.PICC_HaltA(); // Berhentikan pembacaan tag
  mfrc522Keluar.PCD_StopCrypto1(); // Hentikan enkripsi

  availParkir++;
}

// Fungsi untuk membuka palang (servo)
void openExitGate() {
  servoKeluar.write(90);
}

// Fungsi untuk menutup palang (servo)
void closeExitGate() {
  servoKeluar.write(0);
}
