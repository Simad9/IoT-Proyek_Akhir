<?php
include 'koneksi.php';
include 'function.php';

$id_kartu = $_GET["id_kartu"]; // ambil id kartu, yang ada di kartu + sesuai dengan user di db
$tarif = 5000;

echo "Keluar - ";
echo $id_kartu;

if ($id_kartu != "") {
  if (cekSaldo($id_kartu) >= $tarif) {
    $data = palangKeluar(getIdUser($id_kartu)); // Pencatatan parkir
    kurangiSaldo($id_kartu, $tarif);    // Saldo berkurang

    if ($data) {
      echo "Berhasil mengirim data.";
    } else {
      echo "Gagal membaca data: " . mysqli_error($koneksi);
    }
  }
} else {
  echo "Sensor Error";
}

// // Misal button adalah portal
// if (isset($_POST['submit'])) {
//   //cek apakah kartu benar & saldo cukup
//   if (cekSaldo($id_kartu) >= $tarif) {
//     // Jika sensor mendeteksi kendaraan
//     if ($sensor) {
//       palangKeluar(getIdUser($id_kartu)); // Pencatatan parkir
//       kurangiSaldo($id_kartu, $tarif);    // Saldo berkurang

//       echo "Pintu Kebuka";
//       echo "<br>";
//       echo "Uang anda tinggal = " . cekSaldo($id_kartu);
      
//     } else {
//       echo "Eror pak";
//     }
//   }
// }