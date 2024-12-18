<?php
$koneksi = mysqli_connect("localhost", "root", "", "projek_akhir_iot");
if (!$koneksi) {
  die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT count(*) as total FROM slot_parkir WHERE status = 'kosong'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../src/output.css" />
  <link rel="icon" href="../img/icon-parkir.svg" type="image/x-icon" />
  <title>IoT | Dashboard</title>
</head>

<body class="overflow-hidden">
  <div class="p-3 w-full flex gap-[20px] h-screen">
    <!-- side bar -->
    <div
      class="w-[8%] h-full bg-biru rounded-[20px] flex flex-col items-center p-5 justify-between shadow-xl">
      <div class="flex flex-col gap-5 items-center">
        <img src="../img/icon-parkir.svg" alt="Icon Logo" class="w-[40px]" />
        <div class="h-[5px] bg-putih rounded-md w-[110%]"></div>
        <div class="flex flex-col gap-5">
          <a href="dashboard.php">
            <img
              src="../img/icon-dashboard-active.svg"
              alt="dashboard active"
              class="w-[40px]" />
          </a>
          <a href="data.php">
            <img src="../img/icon-data.svg" alt="" class="w-[40px]" />
          </a>
        </div>
      </div>
      <!-- <img src="../img/icon-logout.svg" alt="" class="w-[40px]" /> -->
    </div>

    <!-- Kanan -->
    <div class="w-full h-screen flex flex-col gap-[20px]">
      <!-- Header -->
      <div
        class="w-full bg-putih border rounded-[20px] shadow-xl py-3 px-5 flex justify-between items-center">
        <!-- NOTE : dipilih aja yang atas mau sapaan atau search -->
        <!-- ex: search -->
        <!-- <div class="flex gap-3 bg-[#ECEFF5] rounded-lg p-2 w-[300px]">
            <label for="search">
              <img src="../img/icon-search.svg" alt="" />
            </label>
            <input
              type="text"
              placeholder="Cari disini"
              id="search"
              class="bg-[#ECEFF5] focus:outline-none focus:ring-0"
            />
          </div> -->
        <!-- ex: sapaan -->
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
        <!-- Grafik -->
        <div class="w-[60%] bg-putih border rounded-[20px] shadow-xl p-5">
          <div class="ps-3 mb-3">
            <h2 class="text-2xl font-semibold">GRAFIK</h2>
          </div>
          <canvas
            id="myChart"
            class="border border-biru rounded-lg p-2 w-[750px]"></canvas>
        </div>
        <!-- Detail -->
        <div class="flex flex-col w-full gap-[20px]">
          <!-- Jam -->
          <div
            class="bg-putih border rounded-[20px] shadow-xl p-5 w-full h-full flex flex-col">
            <div class="ps-3 mb-3">
              <h2 class="text-2xl font-semibold text-center">
                WAKTU SAAT INI
              </h2>
            </div>
            <div class="flex items-center justify-center px-5">
              <p class="font-digital font-semibold text-[100px]" id="clock">20:00:00</p>
            </div>
          </div>
          <!-- Slot Parkir -->
          <div
            class="bg-putih border rounded-[20px] shadow-xl p-5 w-full h-full flex flex-col item-center">
            <div class="ps-3 mb-3">
              <h2 class="text-2xl font-semibold text-center">
                PARKIR TERSEDIA
              </h2>
            </div>
            <div class="flex flex-col items-center justify-center">
              <p class="font-semibold text-hitam text-[60px] mb-2" id="slot">3/3</p>
              <div
                class="w-[70%] bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-2">
                <div
                  class="bg-blue-600 h-2.5 rounded-full"
                  style="width: <?= round($data["total"] / 3 * 100, 2) ?>%" id="styleSlot"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- BAGIAN BAWAH -->
      <div class="w-full flex gap-[20px]">
        <!-- Info Dev -->
        <div
          class="w-[50%] bg-putih border rounded-[20px] shadow-xl py-3 px-4 flex justify-between h-full">
          <div class="flex justify-start items-start gap-3 w-full">
            <img src="../img/icon-proyek.svg" alt="" class="w-[40px]" />
            <div>
              <h1 class="font-semibold text-2xl text-hitam">Proyek IoT</h1>
              <p>
                Proyek Internet of Things kami adalah Parkir. Dengan ketentuan
                seperti yang dijelaskan...
              </p>
            </div>
          </div>
          <div class="flex justify-end">
            <button class="bg-biru text-putih px-5 py-2 rounded-lg">
              Lihat Detail
            </button>
          </div>
        </div>

        <!-- Info Team -->
        <div
          class="w-[50%] bg-putih border rounded-[20px] shadow-xl py-3 px-4 flex justify-between h-full">
          <div class="flex justify-start items-start gap-3 w-full">
            <img src="../img/icon-team.svg" alt="" class="w-[40px]" />
            <div class="pt-3">
              <h1 class="font-semibold text-2xl text-hitam">Team Kami</h1>
              <p>Team kami terdiri dari 5 orang yaitu...</p>
            </div>
          </div>
          <div class="flex justify-end">
            <button class="bg-biru text-putih px-5 py-2 rounded-lg">
              Lihat Detail
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- SCRIPT JS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Grafik
    const ctx = document.getElementById("myChart");

    const today = new Date();
    const dates = [];
    for (let i = 6; i >= 0; i--) {
      const date = new Date(today - 1000 * 60 * 60 * 24 * i);
      const day = date.getDate();
      const month = date.toLocaleString("default", {
        month: "short",
      });
      dates.push(`${day} ${month}`);
    }

    new Chart(ctx, {
      type: "line",
      data: {
        labels: dates,
        datasets: [{
          label: "Total Mobil Masuk",
          data: Array.from({
              length: 7
            },
            () => Math.floor(Math.random() * 10) + 1
          ),
          // borderWidth: 1,
        }, ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });

    // Realtime Waktu
    function updateTime() {
      const clockElement = document.getElementById('clock');
      const now = new Date();

      // Format waktu
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');

      // Update elemen dengan waktu saat ini
      clockElement.textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Panggil updateTime setiap detik
    setInterval(updateTime, 1000);

    // Jalankan sekali agar langsung terlihat tanpa menunggu 1 detik
    updateTime();
  </script>
  <script>
    let slot = document.getElementById("slot");
    let styleSlot = document.getElementById("styleSlot");
    let lastTotal = -1;

    function checkSlots() {
      fetch(`long_poll.php?last_total=${lastTotal}`)
        .then(response => response.json())
        .then(data => {
          if (data.total !== undefined) {
            lastTotal = data.total;
            slot.innerHTML = `${lastTotal} / 3`;
            styleSlot.style.width = `${lastTotal / 3 * 100}%`;  
          }
          checkSlots(); // Lanjutkan polling
        })
        .catch(err => {
          console.error('Error:', err);
          setTimeout(checkSlots, 5000); // Coba lagi setelah 5 detik jika ada error
        });
    }
    checkSlots(); // Mulai polling
  </script>
</body>

</html>