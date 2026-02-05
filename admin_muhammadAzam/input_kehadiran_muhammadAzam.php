<?php
include "../config_muhammadAzam/session_muhammadAzam.php";
include "../config_muhammadAzam/koneksi_muhammadAzam.php";
include "../config_muhammadAzam/functions_muhammadAzam.php";

if ($_SESSION['role_muhammadAzam'] != 'admin') {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}

$id_kehadiran = generate_id('tb_kehadiran_muhammadAzam','HDR-','id_kehadiran',$koneksi);

if(isset($_POST['submit'])){
    $rfid = $_POST['rfid'];
    $status = $_POST['status'];
    $tanggal = $_POST['tanggal'];

    $query = "INSERT INTO tb_kehadiran_muhammadAzam
              (id_kehadiran, rfid_muhammadAzam, status_kehadiran, tanggal_kehadiran)
              VALUES ('$id_kehadiran','$rfid','$status','$tanggal')";
    mysqli_query($koneksi,$query);
    header("Location: data_kehadiran_muhammadAzam.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Kehadiran | Admin</title>
<link rel="stylesheet" href="style_admin_muhammadAzam.css">
</head>
<body>
<?php include "navbar_admin.php"; ?>
<div class="main">
<h2>Input Kehadiran Siswa</h2>
<div class="form-card">
<form method="POST">
<label>RFID Siswa</label>
<input type="text" name="rfid" placeholder="UID kartu RFID" required>
<label>Status Kehadiran</label>
<select name="status" required>
    <option value="">-- Pilih Status --</option>
    <option value="Hadir">Hadir</option>
    <option value="Izin">Izin</option>
    <option value="Sakit">Sakit</option>
    <option value="Alfa">Alfa</option>
</select>
<label>Tanggal</label>
<input type="date" name="tanggal" required>
<button type="submit" name="submit">Simpan Kehadiran</button>
</form>
</div>
</div>
</body>
</html>
