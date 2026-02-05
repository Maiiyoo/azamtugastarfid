<?php
// ===================================================
// CETAK RAPOR MASSAL OLEH ADMIN MENGGUNAKAN FPDF
// ===================================================

// Mengecek session login
include "../config_muhammadAzam/session_muhammadAzam.php";

// Memastikan role admin
if ($_SESSION['role_muhammadAzam'] != 'admin') {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}

// Koneksi database
include "../config_muhammadAzam/koneksi_muhammadAzam.php";

// Memanggil library FPDF
require "../fpdf_muhammadAzam/fpdf.php";

// ===================================================
// FORM FILTER (KELAS, SEMESTER, TAHUN)
// ===================================================
if (!isset($_POST['cetak'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Rapor Massal</title>
    <link rel="stylesheet" href="../assets_muhammadAzam/css/global_muhammadAzam.css">
<link rel="stylesheet" href="../assets_muhammadAzam/css/rapor_muhammadAzam.css">

</head>
<body>

<h2>Filter Cetak Rapor Massal</h2>

<form method="POST">
    Kelas :
    <input type="text" name="kelas_muhammadAzam" required><br><br>

    Semester :
    <select name="semester_muhammadAzam" required>
        <option value="Ganjil">Ganjil</option>
        <option value="Genap">Genap</option>
    </select><br><br>

    Tahun Ajaran :
    <input type="text" name="tahun_ajaran_muhammadAzam" placeholder="2025/2026" required><br><br>

    <button type="submit" name="cetak">Cetak Rapor</button>
</form>

</body>
</html>
<?php
exit;
}
// ===================================================
// PROSES CETAK RAPOR
// ===================================================

// Mengambil filter dari form
$kelas_muhammadAzam    = $_POST['kelas_muhammadAzam'];
$semester_muhammadAzam = $_POST['semester_muhammadAzam'];
$tahun_muhammadAzam    = $_POST['tahun_ajaran_muhammadAzam'];

// Membuat objek PDF
$pdf = new FPDF();
$pdf->SetAutoPageBreak(true, 15);

// ===================================================
// QUERY SEMUA MURID SESUAI KELAS
// ===================================================
$query_murid_muhammadAzam = mysqli_query(
    $koneksi_muhammadAzam,
    "SELECT * FROM tb_murid_muhammadAzam
     WHERE kelas_muhammadAzam = '$kelas_muhammadAzam'"
);

// Loop setiap murid → 1 halaman rapor
while ($murid = mysqli_fetch_assoc($query_murid_muhammadAzam)) {

    // Menambahkan halaman baru untuk murid
    $pdf->AddPage();

    // ===============================
    // JUDUL RAPOR
    // ===============================
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(190,10,'RAPOR PESERTA DIDIK',0,1,'C');
    $pdf->Ln(5);

    // ===============================
    // BIODATA MURID
    // ===============================
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,'Nama',0,0);
    $pdf->Cell(5,8,':',0,0);
    $pdf->Cell(100,8,$murid['nama_lengkap_muhammadAzam'],0,1);

    $pdf->Cell(50,8,'NIS',0,0);
    $pdf->Cell(5,8,':',0,0);
    $pdf->Cell(100,8,$murid['nis_muhammadAzam'],0,1);

    $pdf->Cell(50,8,'Kelas',0,0);
    $pdf->Cell(5,8,':',0,0);
    $pdf->Cell(100,8,$kelas_muhammadAzam,0,1);

    $pdf->Cell(50,8,'Semester',0,0);
    $pdf->Cell(5,8,':',0,0);
    $pdf->Cell(100,8,$semester_muhammadAzam,0,1);

    $pdf->Cell(50,8,'Tahun Ajaran',0,0);
    $pdf->Cell(5,8,':',0,0);
    $pdf->Cell(100,8,$tahun_muhammadAzam,0,1);

    $pdf->Ln(5);

    // ===============================
    // TABEL NILAI
    // ===============================
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(10,8,'No',1,0,'C');
    $pdf->Cell(110,8,'Mata Pelajaran',1,0,'C');
    $pdf->Cell(30,8,'Nilai',1,1,'C');

    $pdf->SetFont('Arial','',10);

    // Query nilai murid
    $query_nilai_muhammadAzam = mysqli_query(
        $koneksi_muhammadAzam,
        "SELECT * FROM tb_nilai_muhammadAzam
         WHERE rfid_muhammadAzam = '{$murid['rfid_muhammadAzam']}'
         AND kelas_muhammadAzam = '$kelas_muhammadAzam'
         AND semester_muhammadAzam = '$semester_muhammadAzam'
         AND tahun_ajaran_muhammadAzam = '$tahun_muhammadAzam'"
    );

    $no = 1;

    while ($nilai = mysqli_fetch_assoc($query_nilai_muhammadAzam)) {
        $pdf->Cell(10,8,$no++,1,0,'C');
        $pdf->Cell(110,8,$nilai['mapel_muhammadAzam'],1,0);
        $pdf->Cell(30,8,$nilai['nilai_muhammadAzam'],1,1,'C');
    }

    $pdf->Ln(5);

    // ===============================
    // REKAP KEHADIRAN 6 BULAN
    // ===============================
    $query_kehadiran_muhammadAzam = mysqli_query(
        $koneksi_muhammadAzam,
        "SELECT 
            SUM(status_muhammadAzam='Hadir') AS hadir,
            SUM(status_muhammadAzam='Sakit') AS sakit,
            SUM(status_muhammadAzam='Izin') AS izin,
            SUM(status_muhammadAzam='Alpha') AS alpha
         FROM tb_kehadiran_muhammadAzam
         WHERE rfid_muhammadAzam = '{$murid['rfid_muhammadAzam']}'
         AND tanggal_muhammadAzam >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)"
    );

    $rekap = mysqli_fetch_assoc($query_kehadiran_muhammadAzam);

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(190,8,'Rekap Kehadiran 6 Bulan Terakhir',0,1);

    $pdf->SetFont('Arial','',10);
    $pdf->Cell(50,8,'Hadir',1,0);
    $pdf->Cell(30,8,$rekap['hadir'].' Hari',1,1);

    $pdf->Cell(50,8,'Sakit',1,0);
    $pdf->Cell(30,8,$rekap['sakit'].' Hari',1,1);

    $pdf->Cell(50,8,'Izin',1,0);
    $pdf->Cell(30,8,$rekap['izin'].' Hari',1,1);

    $pdf->Cell(50,8,'Alpha',1,0);
    $pdf->Cell(30,8,$rekap['alpha'].' Hari',1,1);

    // ===============================
    // TANDA TANGAN
    // ===============================
    $pdf->Ln(15);
    $pdf->Cell(120,8,'',0,0);
    $pdf->Cell(60,8,'Wali Kelas',0,1,'C');

    $pdf->Ln(20);
    $pdf->Cell(120,8,'',0,0);
    $pdf->Cell(60,8,'_____________________',0,1,'C');
}

// Output PDF ke browser
$pdf->Output();
