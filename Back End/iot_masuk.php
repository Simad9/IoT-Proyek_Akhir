<?php
// Ketika Mobil Masuk
include 'koneksi.php';
include 'function.php';

$tarif = 5000;
$sensor = true; // nanti ambil data dari IoT pake gget
$id_kartu = 12345; // ambil data dari RFID nanti
$slot_parkir = getSlotParkir();

echo "Pintu Keluar";
echo "<br>";
echo "Slot Parkir : $slot_parkir";
echo "<br>";


// Misal button adalah portal
if (isset($_POST['submit'])) {
  //cek apakah kartu benar & saldo cukup
  if (cekKartu($id_kartu) && cekSaldo($id_kartu) >= $tarif) {
    // Jika sensor mendeteksi kendaraan
    if ($sensor) {
      echo "Pintu Kebuka";
      echo "<br>";
      echo "Mobil dari id : `" . getIdUser($id_kartu) . "` telah Masuk ke parkiran";
      echo "<br>";
      palangMasuk(getIdUser($id_kartu)); // Palang Terbuka
    }
  } else {
    echo "Eror pak";
  }
}

echo "<br>";
?>

<form action="" method="post">
  <input type="submit" value="submit" name="submit"> Portal Buka
</form>

<a href="iot_masuk.php">Ke Pintu Masuk</a>
<br>
<a href="iot_parkir.php">Ke Parkiranr</a>
<br>
<a href="iot_keluar.php">Ke Pintu Keluar</a>