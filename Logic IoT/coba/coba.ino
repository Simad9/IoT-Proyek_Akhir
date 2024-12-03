#include <Arduino.h>
#include <SPI.h>
#include <MFRC522.h>
#include <ESP32Servo.h>

// Deklarasi untuk RFID Masuk
#define RFID_MASUK_SDA 2

#define RFID_RST 4

#define RFID_KELUAR_SDA 14
MFRC522 RFID_KELUAR(RFID_KELUAR_SDA, RFID_RST); // 
MFRC522 RFID_MASUK(RFID_MASUK_SDA, RFID_RST); // Instance untuk RFID Masuk

// Deklarasi untuk RFID KeluarInstance untuk RFID Keluar

// Deklarasi Untuk Infrared Masuk
#define INFRARED_MASUK 22

// Deklarasi Untuk Servo Masuk
#define SERVO_MASUK 23
Servo pintuMasukServo; // Servo untuk pintu

void setup() {
  Serial.begin(9600);
  SPI.begin(5, 19, 18, 23); // Init SPI bus dengan pin manual, pin 23 adalah SS untuk SPI
  
  RFID_KELUAR.PCD_Init();  // Inisialisasi RFID Keluar
  RFID_MASUK.PCD_Init();   // Inisialisasi RFID Masuk
  
  pinMode(INFRARED_MASUK, INPUT);
  pintuMasukServo.attach(SERVO_MASUK);
}

void loop() {
  if (digitalRead(INFRARED_MASUK) == LOW) {
    pintuMasukServo.write(90);  // Pintu terbuka
  } else {
    pintuMasukServo.write(0);   // Pintu tertutup
  }

  // Pembacaan untuk RFID Keluar
  if (RFID_KELUAR.PICC_IsNewCardPresent()) {
    if (RFID_KELUAR.PICC_ReadCardSerial()) {
      Serial.print("Data RFID Keluar: ");
      for (byte i = 0; i < RFID_KELUAR.uid.size; i++) {
        Serial.print(RFID_KELUAR.uid.uidByte[i], HEX);
      }
      Serial.println();
      RFID_KELUAR.PICC_HaltA();  // Menghentikan kartu setelah pembacaan
      RFID_KELUAR.PCD_StopCrypto1();  // Matikan enkripsi
    } else {
      Serial.println("Gagal membaca kartu RFID Keluar.");
    }
  }

  // Pembacaan untuk RFID Masuk
  if (RFID_MASUK.PICC_IsNewCardPresent()) {
    if (RFID_MASUK.PICC_ReadCardSerial()) {
      Serial.print("Data RFID Masuk: ");
      for (byte i = 0; i < RFID_MASUK.uid.size; i++) {
        Serial.print(RFID_MASUK.uid.uidByte[i], HEX);
      }
      Serial.println();
      RFID_MASUK.PICC_HaltA();  // Menghentikan kartu setelah pembacaan
      RFID_MASUK.PCD_StopCrypto1();  // Matikan enkripsi
    } else {
      Serial.println("Gagal membaca kartu RFID Masuk.");
    }
  }

  delay(1000);  // Delay 1 detik
}
