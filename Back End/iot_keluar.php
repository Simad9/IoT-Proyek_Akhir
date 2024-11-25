<?php
// Ketika Mobil Masuk
include 'koneksi.php';
include 'function.php';


$tarif = 5000;
$sensor = true; // nanti ambil data dari IoT pake gget
$id_kartu = 54321; // ambil data dari RFID nanti
$slot_parkir = getSlotParkir();

echo "Pintu Keluar";
echo "<br>";
echo "Slot Parkir : $slot_parkir";
echo "<br>";


// Misal button adalah portal
if (isset($_POST['submit'])) {
  //cek apakah kartu benar & saldo cukup
  if (cekSaldo($id_kartu) >= $tarif) {
    // Jika sensor mendeteksi kendaraan
    if ($sensor) {
      palangKeluar(getIdUser($id_kartu)); // Pencatatan parkir
      kurangiSaldo($id_kartu, $tarif);    // Saldo berkurang

      echo "Pintu Kebuka";
      echo "<br>";
      echo "Uang anda tinggal = " . cekSaldo($id_kartu);
      
    } else {
      echo "Eror pak";
    }
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