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
