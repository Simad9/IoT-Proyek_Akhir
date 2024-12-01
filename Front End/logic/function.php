<?php
include 'koneksi.php';

// Ngecek Kartu dah bener belum
function cekKartu($id_kartu)
{
  global $conn;
  $query = "SELECT * FROM user WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($conn, $query);
  return $result;
}

// Ngecek Saldo
function cekSaldo($id_kartu)
{
  global $conn;
  $query = "SELECT * FROM user WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);
  return $data['saldo'];
}


function cekSudahMasuk($id_user)
{
  global $conn;
  $query = "SELECT * FROM sistem WHERE id_user = '$id_user'";
  $result = mysqli_query($conn, $query);
  return $result;
}

// Kurangi Saldo
function kurangiSaldo($id_kartu, $tarif)
{
  global $conn;
  $query = "UPDATE user SET saldo = saldo - '$tarif' WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($conn, $query);
  return $result;
}

// ambil id_user
function getIdUser($id_kartu)
{
  global $conn;
  $query = "SELECT id_user FROM user WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);
  return $data['id_user'];
}

function getSlotParkir()
{
  global $conn;
  $query = "SELECT COUNT(*) AS total FROM slot_parkir WHERE status = 'kosong'";
  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_assoc($result);
  return $data['total'];
}

function palangMasuk($id_user)
{
  global $conn;
  $query = "INSERT INTO `sistem` (`id_sistem`, `harga`, `waktuMasuk`, `waktuKeluar`, `id_user`) 
  VALUES (NULL, '5000', NOW() , NULL , '$id_user')";
  $result = mysqli_query($conn, $query);
  // echo "Data ditambahkan \n";
  return $result;
}

function palangKeluar($id_user)
{
  global $conn;
  $query = "UPDATE sistem
  SET waktuKeluar = NOW() 
  WHERE id_user = '$id_user'";
  $result = mysqli_query($conn, $query);
  // echo "Data diupdate \n";
  return $result;
}

function updateSlotParkir($slot)
{
  global $conn;
  $query = "UPDATE slot_parkir 
  SET status = 'terisi', waktuParkir = NOW()
  WHERE id_slot = '$slot'";
  $result = mysqli_query($conn, $query);
  echo "Data diupdate - ISI \n";
  return $result;
}

function updateSlotParkirKeluar($slot)
{
  global $conn;
  $query = "UPDATE slot_parkir 
  SET status = 'kosong', waktuParkir = NULL
  WHERE id_slot = '$slot'";
  $result = mysqli_query($conn, $query);
  echo "Data diupdate - NULL \n";
  return $result;
}
