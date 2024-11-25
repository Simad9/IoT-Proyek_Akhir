<?php
// Konfigurasi koneksi database
$host = "localhost"; // Host MySQL
$username = "root";  // Username MySQL
$password = "";      // Password MySQL
$database = "projek_akhir_iot"; // Nama database yang digunakan

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
