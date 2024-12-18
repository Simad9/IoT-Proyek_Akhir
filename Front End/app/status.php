<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "projek_akhir_iot");
if (!$koneksi) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['login'])) {
  $id_user = $_SESSION['id_user'];
  $query = "SELECT * FROM user WHERE id_user = '$id_user'";
  $result = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_assoc($result);
} else {
  header('location: login.php?status=gagal');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" href="../img/icon-parkir.svg" type="image/x-icon" />
  <title>IoT | Status </title>
</head>

<body>
  <div class="p-3 w-full flex flex-col md:flex-row gap-5 h-screen md:h-screen">
    <!-- Gambar -->
    <div class="w-full md:w-[35%] h-[135px] md:h-full rounded-[20px] bg-[#6D90D0] flex md:flex-col items-center justify-between  shadow-lg">
      <img src="../img/login-hiasanAtas.svg" alt="hiasan atas" class="w-[25%] -rotate-90 -translate-x-[25px] md:rotate-0 md:translate-x-0 md:w-[60%]">
      <img src="../img/login-tulisan.svg" alt="tulisan" class="w-[25%] md:w-5/12">
      <img src="../img/login-hiasanBawah.svg" alt="hiasan bawah" class="w-[25%] -rotate-90 translate-x-[25px] md:rotate-0 md:translate-x-0 md:w-[60%]">
    </div>
    <!-- Login -->
    <div class="w-full md:[65%] h-full border p-[25px] md:p-[50px] rounded-[20px] shadow-lg flex flex-col gap-2 md:gap-4 justify-start md:justify-center">
      <div>
        <h1 class="text-3xl md:text-4xl text-hitam font-semibold mb-2">Status Anda</h1>
      </div>

      <div class="mb-3">
        <table class="table-auto text-hitam font-medium">
          <tr>
            <td>Username</td>
            <td class="px-2">:</td>
            <td><?= $data['username'] ?></td>
          </tr>
          <tr>
            <td>ID Kartu</td>
            <td class="px-2">:</td>
            <td><?= $data['id_kartu'] ?></td>
          </tr>
          <tr>
            <td>Saldo</td>
            <td class="px-2">:</td>
            <td>Rp. <?= number_format($data['saldo'], 0, ',', '.') ?></td>
          </tr>
        </table>
      </div>

      <div>
        <p class="font-normal text-hitam text-sm mb-3">Jika saldo anda habis, silahkan hubungi admin</p>
        <a href="login.php"><button class="bg-red-600 hover:bg-red-600/70 rounded-md w-full py-2 text-white margin-auto">Keluar<button></a>
      </div>

    </div>
  </div>
</body>

</html>