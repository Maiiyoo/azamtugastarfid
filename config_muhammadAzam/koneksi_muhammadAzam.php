<?php
// File koneksi database MySQL

$host_muhammadAzam = "localhost";      // Host database
$user_muhammadAzam = "root";           // Username database
$pass_muhammadAzam = "";               // Password database
$db_muhammadAzam   = "db_rfid_muhammadAzam"; // Nama database

// Membuat koneksi
$koneksi_muhammadAzam = mysqli_connect(
    $host_muhammadAzam,
    $user_muhammadAzam,
    $pass_muhammadAzam,
    $db_muhammadAzam
);

// Validasi koneksi
if (!$koneksi_muhammadAzam) {
    die("Koneksi database gagal");
}
?>
