<?php
session_start();
session_destroy();
session_start();

$koneksi = mysqli_connect("localhost", "root", "", "projek_akhir_iot");
if (!$koneksi) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];
  var_dump($username);

  if ($username == "admin" && $password == "admin") {
    header('location: dashboard.php');
  } else {
    $query = "SELECT * FROM user WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
      $data = mysqli_fetch_assoc($result);
      $_SESSION['id_user'] = $data['id_user'];
      $_SESSION['login'] = true;
      header('location: status.php');
    } else {
      header('location: login.php?status=gagal');
    }
  }
}

if (isset($_POST["status"])) {
  switch ($_POST['status']) {
    case 'gagal':
      echo "<script>alert('Salah username atau password')</script>";
      break;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../src/output.css">
  <link rel="icon" href="../img/icon-parkir.svg" type="image/x-icon" />
  <title>IoT | Login</title>
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
    <div class="w-full md:[65%] h-full border p-[50px] rounded-[20px] shadow-lg flex flex-col gap-2 md:gap-4 justify-start md:justify-center text-hitam">
      <div>
        <h1 class="text-4xl font-semibold mb-2">Login</h1>
        <p class="font-medium text-base">Silahkan Login terlebih dahulu untuk akses web ini</p>
      </div>
      <form action="" method="post">
        <div>
          <div class="mb-3 flex flex-col">
            <label for="username" class="font-medium mb-1">Username</label>
            <input type="text" id="username" class="bg-[#D9D9D9] rounded-md p-2 " placeholder="Masukan Username" name="username" required>
          </div>
          <div class="mb-3 flex flex-col">
            <label for="password" class="font-medium mb-1">Password</label>
            <input type="password" id="password" class="bg-[#D9D9D9] rounded-md p-2 " placeholder="Masukan Password" name="password" required>
          </div>
        </div>


        <div>
          <div class="w-full mb-3">
            <button class="bg-[#6D90D0] hover:bg-[#6D90D0]/80 rounded-md w-full py-2 text-white margin-auto" type="submit" name="submit">Login<button>
          </div>
          <p class="font-normal text-sm">Silhakan hubungi admin untuk mendaftarkan akun</p>
        </div>
      </form>

    </div>
  </div>
</body>

</html>