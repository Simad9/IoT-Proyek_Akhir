<?php
include 'koneksi.php';
include 'function.php';

$status = $_GET['status']; //status sensor : teriisi / kosong
$id_slot = $_GET['id_slot']; // urutan slot parkirnya

if ($status != "" && $id_slot != "") {
  $data = mysqli_query(
    $koneksi,
    "UPDATE slot_parkir SET 
    status = $status,
    waktuParkir = NOW()
    WHERE id_slot = '$id_slot'"
  );

  if ($data) {
    echo "Berhasil mengirim data.";
  } else {
    echo "Gagal membaca data: " . mysqli_error($koneksi);
  }
} else {
  echo "Sensor Error";
}
