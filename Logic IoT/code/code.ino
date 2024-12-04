#include <Arduino.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

#include <SPI.h>
#include <MFRC522.h>
#include <ESP32Servo.h>

//Deklarasi RFID
#define RFID_RST 4

//Deklarasi Umum Masuk
String lastRFIDMasuk = "";
String currentRFIDMasuk = "";
bool isGateMasukOpen = false;
int parkiranTersedia = 3;
int infraredMasukStatus = 0;

//Deklarasi Umum Keluar
String lastRFIDKeluar = "";
String currentRFIDKeluar = "";
bool isGateKeluarOpen = false;
int infraredKeluarStatus = 0;

#define RFID_MASUK_SDA 2
MFRC522 RFID_MASUK;

// Deklarasi Untuk Infrared Masuk
#define INFRARED_MASUK 22

// Deklarasi Untuk Servo Masuk
#define SERVO_MASUK 23
Servo pintuMasukServo; // Servo untuk pintu

//Deklarasi Untuk LCD Masuk
#define LCD_MASUK_SDA 33
#define LCD_MASUK_SCL 32
LiquidCrystal_I2C lcd(0x27, 16, 2);
//LiquidCrystal_I2C lcd2(0x3F, 16, 2);

// Deklarasi untuk RFID Keluar
#define RFID_KELUAR_SDA 14
MFRC522 RFID_KELUAR; // Instance untuk RFID Keluar

// Deklarasi Untuk Infrared Keluar
#define INFRARED_KELUAR 26

// Deklarasi Untuk Servo Keluar
#define SERVO_KELUAR 25
Servo pintuKeluarServo; // Servo untuk pintu

void taskPintuMasuk(void *pvParameters){
  while(true){
    if(!isGateMasukOpen){
      if(RFID_MASUK.PICC_IsNewCardPresent()){
        if(RFID_MASUK.PICC_ReadCardSerial()){
          currentRFIDMasuk = "";
          for (byte i = 0; i < RFID_MASUK.uid.size; i++) {
            currentRFIDMasuk += String(RFID_MASUK.uid.uidByte[i], HEX);
          }

          if(currentRFIDMasuk.equalsIgnoreCase(lastRFIDMasuk)){
            Serial.println("Kartu telah di-tap sebelumnya!");
          }else{
            Serial.println("Kartu Masuk baru terdeteksi!");
            Serial.print("UID Baru: ");
            Serial.println(currentRFIDMasuk);
            lastRFIDMasuk = currentRFIDMasuk; // Simpan UID baru sebagai UID terakhir
            isGateMasukOpen = true;
            // lcd.clear();
            // lcd.setCursor(0, 0);
            // lcd.print("Mobil Masuk");
            pintuMasukServo.write(90);
          }
          RFID_MASUK.PICC_HaltA();
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
          pintuMasukServo.write(0);
          parkiranTersedia--;
          // lcd.clear();
          // lcd.setCursor(0, 0);
          // lcd.print("Sisa Slot :");
          // lcd.setCursor(0, 1);
          // lcd.print(parkiranTersedia);
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
          currentRFIDKeluar = "";
          for (byte i = 0; i < RFID_KELUAR.uid.size; i++) {
            currentRFIDKeluar += String(RFID_KELUAR.uid.uidByte[i], HEX);
          }

          if(currentRFIDKeluar.equalsIgnoreCase(lastRFIDKeluar)){
            Serial.println("Kartu telah di-tap sebelumnya!");
          }else{
            Serial.println("Kartu Keluar baru terdeteksi!");
            Serial.print("UID Baru: ");
            Serial.println(currentRFIDKeluar);
            lastRFIDKeluar = currentRFIDKeluar; // Simpan UID baru sebagai UID terakhir
            isGateKeluarOpen = true;
            pintuKeluarServo.write(90);
          }
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

  //Inisialisasi RFID
  SPI.begin(5, 19, 18, 4); // SCK, MISO, MOSI
  RFID_MASUK.PCD_Init(RFID_MASUK_SDA, RFID_RST);   // Inisialisasi RFID Masuk
  RFID_KELUAR.PCD_Init(RFID_KELUAR_SDA, RFID_RST);  // Inisialisasi RFID Keluar

  //Inisial LCD
  Wire.begin(LCD_MASUK_SDA, LCD_MASUK_SCL);  // SDA di GPIO 14, SCL di GPIO 13
  // Inisialisasi LCD
  lcd.begin();
  lcd.backlight();      // Mengaktifkan lampu latar LCD

  // // Menampilkan pesan pada LCD
  lcd.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
  lcd.print("Sisa Slot :");

  lcd.setCursor(0, 1);  // Set cursor di baris kedua
  lcd.print(parkiranTersedia);
  delay(1000);

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
    lcd.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
    lcd.print("Masuk :");
    lcd.setCursor(0, 1);  // Set cursor di baris kedua
    lcd.print(currentRFIDMasuk);
  }else{
    lcd.setCursor(0, 0);  // Set cursor di baris pertama, kolom pertama
    lcd.print("Sisa Slot :");

    lcd.setCursor(0, 1);  // Set cursor di baris kedua
    lcd.print(parkiranTersedia);
  }
  delay(1000);
}