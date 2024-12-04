<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$hostname  = "localhost";
$username  = "root";
$password  = "";
$database  = "projek_akhir_iot";

$koneksi  = new mysqli($hostname, $username, $password, $database); //query koneksi

if ($koneksi->connect_error) { //cek error
  die("Error: " . $connect->connect_error);
}

// Set timezone, biar pas di add ngikutin timezone jakarta
date_default_timezone_set("Asia/Jakarta");
