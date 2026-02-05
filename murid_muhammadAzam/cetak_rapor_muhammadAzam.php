<?php
// ===============================
// CETAK RAPOR MURID MENGGUNAKAN FPDF
// ===============================

// Memastikan murid sudah login
include "../config_muhammadAzam/session_muhammadAzam.php";

// Koneksi database
include "../config_muhammadAzam/koneksi_muhammadAzam.php";

// Memanggil library FPDF
require "../fpdf_muhammadAzam/fpdf.php";

// Mengambil RFID murid dari session
$rfid_muhammadAzam = $_SESSION['rfid_muhammadAzam'];

// Mengambil filter dari halaman nilai
$kelas_muhammadAzam    = $_GET['kelas'];
$semester_muhammadAzam = $_GET['semester'];
$tahun_muhammadAzam    = $_GET['tahun'];

// ===============================
// AMBIL DATA BIODATA MURID
// ===============================
$query_biodata_muhammadAzam = mysqli_query(
    $koneksi_muhammadAzam,
    "SELECT * FROM tb_murid_muhammadAzam 
     WHERE rfid_muhammadAzam = '$rfid_muhammadAzam'"
);

$biodata_muhammadAzam = mysqli_fetch_assoc($query_biodata_muhammadAzam);

// ===============================
// MEMBUAT OBJEK PDF
// ===============================
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);

// ===============================
// JUDUL RAPOR
// ===============================
$pdf->Cell(190,10,'RAPOR PESERTA DIDIK',0,1,'C');
$pdf->Ln(5);

// ===============================
// BIODATA MURID
// ===============================
$pdf->SetFont('Arial','',10);
$pdf->Cell(50,8,'Nama',0,0);
$pdf->Cell(5,8,':',0,0);
$pdf->Cell(80,8,$biodata_muhammadAzam['nama_lengkap_muhammadAzam'],0,1);

$pdf->Cell(50,8,'NIS',0,0);
$pdf->Cell(5,8,':',0,0);
$pdf->Cell(80,8,$biodata_muhammadAzam['nis_muhammadAzam'],0,1);

$pdf->Cell(50,8,'Kelas',0,0);
$pdf->Cell(5,8,':',0,0);
$pdf->Cell(80,8,$kelas_muhammadAzam,0,1);

$pdf->Cell(50,8,'Semester',0,0);
$pdf->Cell(5,8,':',0,0);
$pdf->Cell(80,8,$semester_muhammadAzam,0,1);

$pdf->Cell(50,8,'Tahun Ajaran',0,0);
$pdf->Cell(5,8,':',0,0);
$pdf->Cell(80,8,$tahun_muhammadAzam,0,1);

$pdf->Ln(5);

// ===============================
// TABEL NILAI
// ===============================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,8,'No',1,0,'C');
$pdf->Cell(100,8,'Mata Pelajaran',1,0,'C');
$pdf->Cell(30,8,'Nilai',1,1,'C');

$pdf->SetFont('Arial','',10);

// Query nilai murid
$query_nilai_muhammadAzam = mysqli_query(
    $koneksi_muhammadAzam,
    "SELECT * FROM tb_nilai_muhammadAzam
     WHERE rfid_muhammadAzam = '$rfid_muhammadAzam'
     AND kelas_muhammadAzam = '$kelas_muhammadAzam'
     AND semester_muhammadAzam = '$semester_muhammadAzam'
     AND tahun_ajaran_muhammadAzam = '$tahun_muhammadAzam'"
);

$no_muhammadAzam = 1;

// Loop menampilkan nilai
while ($nilai_muhammadAzam = mysqli_fetch_assoc($query_nilai_muhammadAzam)) {

    $pdf->Cell(10,8,$no_muhammadAzam++,1,0,'C');
    $pdf->Cell(100,8,$nilai_muhammadAzam['mapel_muhammadAzam'],1,0);
    $pdf->Cell(30,8,$nilai_muhammadAzam['nilai_muhammadAzam'],1,1,'C');
}

$pdf->Ln(5);

// ===============================
// REKAP KEHADIRAN 6 BULAN
// ===============================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,8,'Rekap Kehadiran 6 Bulan Terakhir',0,1);

$pdf->SetFont('Arial','',10);

// Query rekap kehadiran
$query_kehadiran_muhammadAzam = mysqli_query(
    $koneksi_muhammadAzam,
    "SELECT 
        SUM(status_muhammadAzam='Hadir') AS hadir,
        SUM(status_muhammadAzam='Sakit') AS sakit,
        SUM(status_muhammadAzam='Izin') AS izin,
        SUM(status_muhammadAzam='Alpha') AS alpha
     FROM tb_kehadiran_muhammadAzam
     WHERE rfid_muhammadAzam = '$rfid_muhammadAzam'
     AND tanggal_muhammadAzam >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)"
);

$kehadiran_muhammadAzam = mysqli_fetch_assoc($query_kehadiran_muhammadAzam);

// Menampilkan hasil rekap
$pdf->Cell(50,8,'Hadir',1,0);
$pdf->Cell(30,8,$kehadiran_muhammadAzam['hadir'].' Hari',1,1);

$pdf->Cell(50,8,'Sakit',1,0);
$pdf->Cell(30,8,$kehadiran_muhammadAzam['sakit'].' Hari',1,1);

$pdf->Cell(50,8,'Izin',1,0);
$pdf->Cell(30,8,$kehadiran_muhammadAzam['izin'].' Hari',1,1);

$pdf->Cell(50,8,'Alpha',1,0);
$pdf->Cell(30,8,$kehadiran_muhammadAzam['alpha'].' Hari',1,1);

// ===============================
// TANDA TANGAN
// ===============================
$pdf->Ln(15);
$pdf->Cell(120,8,'',0,0);
$pdf->Cell(60,8,'Wali Kelas',0,1,'C');

$pdf->Ln(20);
$pdf->Cell(120,8,'',0,0);
$pdf->Cell(60,8,'_____________________',0,1,'C');

// Output PDF
$pdf->Output();
