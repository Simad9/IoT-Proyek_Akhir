<?php
$koneksi = mysqli_connect("localhost", "root", "", "projek_akhir_iot");
if (!$koneksi) {
  die("Connection failed: " . mysqli_connect_error());
}

$timeout = 30; // Waktu maksimum untuk long polling
$start_time = time();
$last_total = isset($_GET['last_total']) ? intval($_GET['last_total']) : -1;

while (true) {
  $query = "SELECT count(*) as total FROM slot_parkir WHERE status = 'kosong'";
  $result = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_array($result);
  $current_total = $data['total'];

  if ($current_total != $last_total) {
    echo json_encode(["total" => $current_total]);
    break;
  }

  if (time() - $start_time > $timeout) {
    echo json_encode([]);
    break;
  }

  usleep(500000); // Tunggu 0,5 detik sebelum cek lagi
}

mysqli_close($koneksi);
