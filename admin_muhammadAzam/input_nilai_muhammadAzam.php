<?php
include "../config_muhammadAzam/session_muhammadAzam.php";
include "../config_muhammadAzam/koneksi_muhammadAzam.php";
include "../config_muhammadAzam/functions_muhammadAzam.php";

if ($_SESSION['role_muhammadAzam'] != 'admin') {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}

$id_nilai = generate_id('tb_nilai_muhammadAzam', 'NIL', 'id_nilai', $koneksi);

if(isset($_POST['submit'])){
    $rfid = $_POST['rfid'];
    $mapel = $_POST['mapel'];
    $nilai = $_POST['nilai'];
    $semester = $_POST['semester'];
    $kelas = $_POST['kelas'];
    $tahun = $_POST['tahun'];

    $query = "INSERT INTO tb_nilai_muhammadAzam
              (id_nilai, rfid_muhammadAzam, mapel_muhammadAzam, nilai_muhammadAzam, semester_muhammadAzam, kelas_muhammadAzam, tahun_ajaran_muhammadAzam)
              VALUES ('$id_nilai','$rfid','$mapel','$nilai','$semester','$kelas','$tahun')";
    mysqli_query($koneksi,$query);
    header("Location: data_nilai_muhammadAzam.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Input Nilai | Admin</title>
<link rel="stylesheet" href="style_admin_muhammadAzam.css">
</head>
<body>
<?php include "navbar_admin.php"; ?>
<div class="main">
<h2>Input Nilai Siswa</h2>
<div class="form-card">
<form method="POST">
    <label>RFID Siswa</label>
    <input type="text" name="rfid" placeholder="UID kartu RFID" required>
    <label>Mata Pelajaran</label>
    <input type="text" name="mapel" placeholder="Contoh: Matematika" required>
    <label>Nilai</label>
    <input type="number" name="nilai" placeholder="0 - 100" required>
    <label>Semester</label>
    <input type="text" name="semester" placeholder="Contoh: 1" required>
    <label>Kelas</label>
    <input type="text" name="kelas" placeholder="Contoh: X IPA 1" required>
    <label>Tahun Ajaran</label>
    <input type="text" name="tahun" placeholder="Contoh: 2025/2026" required>
    <button type="submit" name="submit">Simpan Nilai</button>
</form>
</div>
</div>
</body>
</html>
