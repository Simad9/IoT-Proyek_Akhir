<?php
$koneksi = mysqli_connect("localhost", "root", "", "projek_akhir_iot");
if (!$koneksi) {
  die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM sistem INNER JOIN user ON sistem.id_user = user.id_user";
$hasil = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../src/output.css" />
  <link rel="icon" href="../img/icon-parkir.svg" type="image/x-icon" />
  <title>IoT | Data</title>
  <style>
    /* Sticky Header */
    thead {
      position: sticky;
      top: 0;
      background-color: #f3f4f6;
      /* Sesuaikan dengan background tabel */
      z-index: 10;
      /* Pastikan header tetap di atas konten tabel */
    }

    /* Custom Scrollbar */
    .relative::-webkit-scrollbar {
      width: 8px;
      /* Lebar scrollbar */
    }

    .relative::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
      padding: 10px;
      /* Memberikan jarak sekitar track */
    }

    .relative::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
      min-height: 50px;
      /* Minimum tinggi thumb */
      max-height: 150px;
      /* Maksimum tinggi thumb */
    }

    .relative::-webkit-scrollbar-thumb:hover {
      background: #555;
      /* Warna thumb saat dihover */
    }
  </style>
</head>

<body class="overflow-hidden">
  <div class="p-3 w-full flex gap-[20px] h-screen">
    <!-- Side bar -->
    <div
      class="w-[8%] h-full bg-biru rounded-[20px] flex flex-col items-center p-5 justify-between shadow-xl">
      <div class="flex flex-col gap-5 items-center">
        <img src="../img/icon-parkir.svg" alt="Icon Logo" class="w-[40px]" />
        <div class="h-[5px] bg-putih rounded-md w-[110%]"></div>
        <div class="flex flex-col gap-5">
          <a href="dashboard.php">
            <img
              src="../img/icon-dashboard.svg"
              alt="dashboard active"
              class="w-[40px]" />
          </a>
          <a href="data.php">
            <img src="../img/icon-data-active.svg" alt="" class="w-[40px]" />
          </a>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="w-full h-full flex flex-col gap-[20px]">
      <!-- Header -->
      <div
        class="w-full bg-putih border rounded-[20px] shadow-xl py-3 px-5 flex justify-between items-center">
        <h2 class="font-semibold text-hitam text-base">
          Hallo, Selamat Pagi Admin
        </h2>
        <div class="flex gap-3">
          <img src="../img/icon-setting.svg" alt="" />
          <img src="../img/icon-notif.svg" alt="" />
          <div class="flex gap-2 items-center">
            <img
              src="../img/kucing.jpeg"
              alt=""
              class="rounded-full h-[40px]" />
            <p>Admin</p>
          </div>
        </div>
      </div>

      <!-- Main Feature -->
      <div class="flex gap-[20px]">
        <!-- Table -->
        <div class="w-full bg-putih border rounded-[20px] shadow-xl p-5">
          <div class="ps-3 mb-3">
            <h2 class="text-2xl font-semibold">TABEL DATA</h2>
          </div>

          <!-- Scrollable Table -->
          <div
            class="relative overflow-x-auto rounded-[20px] border border-biru h-[510px] overflow-y-auto">
            <table class="w-full text-sm text-left">
              <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                <tr>
                  <th scope="col" class="px-6 py-3">No</th>
                  <th scope="col" class="px-6 py-3">Username</th>
                  <th scope="col" class="px-6 py-3">Waktu Masuk</th>
                  <th scope="col" class="px-6 py-3">Waktu Keluar</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                while ($data = mysqli_fetch_assoc($hasil)): ?>
                  <tr class="bg-white border-b">
                    <th
                      scope="row"
                      class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                      <?= $i++; ?>
                    </th>
                    <td class="px-6 py-4"><?= $data['username']; ?></td>
                    <td class="px-6 py-4"><?= $data['waktuMasuk']; ?></td>
                    <td class="px-6 py-4"><?= $data['waktuKeluar']; ?></td>
                  </tr>
                <?php endwhile; ?>
             </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>