<?php
include 'koneksi.php';

// Ngecek Kartu dah bener belum
function cekKartu($id_kartu)
{
  global $koneksi;
  $query = "SELECT * FROM user WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($koneksi, $query);
  return $result;
}

// Ngecek Saldo
function cekSaldo($id_kartu)
{
  global $koneksi;
  $query = "SELECT * FROM user WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_assoc($result);
  return $data['saldo'];
}


function cekSudahMasuk($id_user)
{
  global $koneksi;
  $query = "SELECT * FROM sistem WHERE id_user = '$id_user'";
  $result = mysqli_query($koneksi, $query);
  return $result;
}

// Kurangi Saldo
function kurangiSaldo($id_kartu, $tarif)
{
  global $koneksi;
  $query = "UPDATE user SET saldo = saldo - '$tarif' WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($koneksi, $query);
  return $result;
}

// ambil id_user
function getIdUser($id_kartu)
{
  global $koneksi;
  $query = "SELECT id_user FROM user WHERE id_kartu = '$id_kartu'";
  $result = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_assoc($result);
  return $data['id_user'];
}

function getSlotParkir()
{
  global $koneksi;
  $query = "SELECT COUNT(*) AS total FROM slot_parkir WHERE status = 'kosong'";
  $result = mysqli_query($koneksi, $query);
  $data = mysqli_fetch_assoc($result);
  return $data['total'];
}

function palangMasuk($id_user)
{
  global $koneksi;
  $query = "INSERT INTO `sistem` (`id_sistem`, `harga`, `waktuMasuk`, `waktuKeluar`, `id_user`) 
  VALUES ('', '5000', NOW() , NULL , '$id_user')";
  $result = mysqli_query($koneksi, $query);
  // echo "Data ditambahkan \n";
  return $result;
}

function palangKeluar($id_user)
{
  global $koneksi;
  $query = "UPDATE sistem
  SET waktuKeluar = NOW() 
  WHERE id_user = '$id_user'
  ORDER BY id_sistem DESC
  LIMIT 1";
  $result = mysqli_query($koneksi, $query);
  // echo "Data diupdate \n";
  return $result;
}

function updateSlotParkir($slot)
{
  global $koneksi;
  $query = "UPDATE slot_parkir 
  SET status = 'terisi', waktuParkir = NOW()
  WHERE id_slot = '$slot'";
  $result = mysqli_query($koneksi, $query);
  echo "Data diupdate - ISI \n";
  return $result;
}

function updateSlotParkirKeluar($slot)
{
  global $koneksi;
  $query = "UPDATE slot_parkir 
  SET status = 'kosong', waktuParkir = NULL
  WHERE id_slot = '$slot'";
  $result = mysqli_query($koneksi, $query);
  echo "Data diupdate - NULL \n";
  return $result;
}

function tambahUserBaru($id_kartu){
  global $koneksi;
  $query = "INSERT INTO user VALUES ('', 'user', 'user', '10000' , '$id_kartu')";
  $result = mysqli_query($koneksi, $query);
  return $result;
}

