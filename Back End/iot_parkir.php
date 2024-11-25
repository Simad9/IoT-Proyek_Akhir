<?php
include 'koneksi.php';
include 'function.php';

$slot_parkir = getSlotParkir();

echo "Parkiran";
echo "<br>";
echo "Slot Parkir : $slot_parkir";
echo "<br>";

$slot = isset($_POST["slot"]) ? $_POST["slot"] : null; //nanti ambil dari sensor

switch ($slot) {
  case "01":
    echo "Slot 1";
    $slot = $slot == "01" ? "1" : "";
    updateSlotParkir($slot);
    echo "<br>";
    break;
  case "10":
    echo "Slot 1";
    $slot = $slot == "10" ? "1" : "";
    updateSlotParkirKeluar($slot);
    echo "<br>";
    break;
  case "02":
    echo "Slot 2";
    $slot = $slot == "02" ? "2" : "";
    updateSlotParkir($slot);
    echo "<br>";
    break;
  case "20":
    echo "Slot 2";
    $slot = $slot == "20" ? "2" : "";
    updateSlotParkirKeluar($slot);
    echo "<br>";
    break;
  case "03":
    echo "Slot 3";
    $slot = $slot == "03" ? "3" : "";
    updateSlotParkir($slot);
    echo "<br>";
    break;
  case "30":
    echo "Slot 3";
    $slot = $slot == "30" ? "3" : "";
    updateSlotParkirKeluar($slot);
    echo "<br>";
    break;
  default:
    echo "Slot Kosong";
    echo "<br>";
    break;
}
?>

<form action="" method="post">
  <div>
    <input type="submit" value="01" name="slot">
    <input type="submit" value="02" name="slot">
    <input type="submit" value="03" name="slot">
  </div>
  <div>
    <input type="submit" value="10" name="slot">
    <input type="submit" value="20" name="slot">
    <input type="submit" value="30" name="slot">
  </div>
</form>

<a href="iot_masuk.php">Ke Pintu Masuk</a>
<br>
<a href="iot_parkir.php">Ke Parkiran</a>
<br>
<a href="iot_keluar.php">Ke Pintu Keluar</a>