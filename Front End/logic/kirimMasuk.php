<?php
include 'koneksi.php';

$id_kartu = $_GET["id_kartu"]; // ambil id kartu, yang ada di kartu + sesuai dengan user di db
$tarif = 5000;

if ($id_kartu != "") {
  // Cek kartu dan cek saldo kartu dari tabel user
  if (cekKartu($id_kartu) && cekSaldo($id_kartu) >= $tarif) {
    // Jika sensor mendeteksi kendaraan
    $data = palangMasuk(getIdUser($id_kartu));
    // $data = mysqli_query(
    //   $koneksi,
    //   "INSERT INTO sistem VALUES ('', '5000', NOW(), NULL, '$id_kartu')"
    // );

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
//   if (cekKartu($id_kartu) && cekSaldo($id_kartu) >= $tarif) {
//     // Jika sensor mendeteksi kendaraan
//     if ($sensor) {
//       echo "Pintu Kebuka";
//       echo "<br>";
//       echo "Mobil dari id : `" . getIdUser($id_kartu) . "` telah Masuk ke parkiran";
//       echo "<br>";
//       palangMasuk(getIdUser($id_kartu)); // Palang Terbuka
//     }
//   } else {
//     echo "Eror pak";
//   }
// }