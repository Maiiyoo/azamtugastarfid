<?php
// Memulai session (cukup sekali)
session_start();

// Waktu timeout (15 menit)
$timeout_muhammadAzam = 900;

// Cek aktivitas terakhir
if (isset($_SESSION['last_activity_muhammadAzam'])) {

    // Jika sudah lebih dari 15 menit
    if (time() - $_SESSION['last_activity_muhammadAzam'] > $timeout_muhammadAzam) {

        // Hapus session
        session_unset();
        session_destroy();

        // Kembali ke halaman login
        header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
        exit;
    }
}

// Update waktu aktivitas terakhir
$_SESSION['last_activity_muhammadAzam'] = time();

// Cek apakah sudah login
if (!isset($_SESSION['rfid_muhammadAzam'])) {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}
?>
