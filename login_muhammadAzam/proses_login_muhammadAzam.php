<?php
// Memulai session
session_start();

// Menghubungkan database
include "../config_muhammadAzam/koneksi_muhammadAzam.php";

// Mengambil data RFID
$rfid_muhammadAzam = $_POST['rfid_muhammadAzam'];

// Query cek RFID
$query_muhammadAzam = mysqli_query(
    $koneksi_muhammadAzam,
    "SELECT * FROM tb_user_muhammadAzam 
     WHERE rfid_muhammadAzam = '$rfid_muhammadAzam'"
);

// Mengecek apakah RFID ada
if (mysqli_num_rows($query_muhammadAzam) > 0) {

    // Mengambil data user
    $data_muhammadAzam = mysqli_fetch_assoc($query_muhammadAzam);

    // Menyimpan session
    $_SESSION['rfid_muhammadAzam'] = $data_muhammadAzam['rfid_muhammadAzam'];
    $_SESSION['role_muhammadAzam'] = $data_muhammadAzam['role_muhammadAzam'];

    // Redirect berdasarkan role
    if ($data_muhammadAzam['role_muhammadAzam'] == "admin") {
        header("Location: ../admin_muhammadAzam/dashboard_admin_muhammadAzam.php");
    } else {
        header("Location: ../murid_muhammadAzam/dashboard_muhammadAzam.php");
    }

} else {
    // Jika RFID tidak ditemukan
    header("Location: login_rfid_muhammadAzam.php?error=1");
}
?>
