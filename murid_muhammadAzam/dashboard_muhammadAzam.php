<?php
include "../config_muhammadAzam/session_muhammadAzam.php";
include "../config_muhammadAzam/koneksi_muhammadAzam.php";

// =====================
// CEK LOGIN
// =====================
if (!isset($_SESSION['rfid_muhammadAzam']) || $_SESSION['role_muhammadAzam'] != "murid") {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

$rfid = mysqli_real_escape_string($koneksi_muhammadAzam, $_SESSION['rfid_muhammadAzam']);

// =====================
// AMBIL DATA MURID
// =====================
$qMurid = mysqli_query($koneksi_muhammadAzam, "
    SELECT id_murid_muhammadAzam, nama_lengkap_muhammadAzam, kelas_muhammadAzam
    FROM tb_murid_muhammadAzam
    WHERE rfid_muhammadAzam = '$rfid'
    LIMIT 1
");

$murid = mysqli_fetch_assoc($qMurid);

if (!$murid) {
    die("Data murid tidak ditemukan. Pastikan RFID murid ada di tb_murid_muhammadAzam.");
}

$id_murid = $murid['id_murid_muhammadAzam'];
$nama  = $murid['nama_lengkap_muhammadAzam'] ?? "Siswa";
$kelas = $murid['kelas_muhammadAzam'] ?? "-";

// =====================
// HITUNG KEHADIRAN
// =====================
$qAbsen = mysqli_query($koneksi_muhammadAzam, "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status_muhammadAzam = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
        SUM(CASE WHEN status_muhammadAzam = 'Izin' THEN 1 ELSE 0 END) AS izin,
        SUM(CASE WHEN status_muhammadAzam = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
        SUM(CASE WHEN status_muhammadAzam = 'Alpha' THEN 1 ELSE 0 END) AS alpha
    FROM tb_kehadiran_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
");

$absen = mysqli_fetch_assoc($qAbsen);

$totalAbsen = (int)($absen['total'] ?? 0);
$hadir = (int)($absen['hadir'] ?? 0);
$izin  = (int)($absen['izin'] ?? 0);
$sakit = (int)($absen['sakit'] ?? 0);
$alpha = (int)($absen['alpha'] ?? 0);

$persenHadir = ($totalAbsen > 0) ? round(($hadir / $totalAbsen) * 100) : 0;

// =====================
// HITUNG NILAI
// =====================
$qNilai = mysqli_query($koneksi_muhammadAzam, "
    SELECT 
        AVG(nilai_angka_muhammadAzam) AS rata2,
        COUNT(*) AS total_nilai
    FROM tb_nilai_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
");

$nilai = mysqli_fetch_assoc($qNilai);
$rataNilai = (!empty($nilai['rata2'])) ? round($nilai['rata2'], 1) : 0;
$totalNilai = (int)($nilai['total_nilai'] ?? 0);

// =====================
// TOTAL MAPEL
// =====================
$qMapel = mysqli_query($koneksi_muhammadAzam, "SELECT COUNT(*) AS total_mapel FROM tb_mapel_muhammadAzam");
$mapel = mysqli_fetch_assoc($qMapel);
$totalMapel = (int)($mapel['total_mapel'] ?? 0);

// =====================
// ABSENSI TERAKHIR
// =====================
$qLastAbsen = mysqli_query($koneksi_muhammadAzam, "
    SELECT tanggal_muhammadAzam, status_muhammadAzam
    FROM tb_kehadiran_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
    ORDER BY tanggal_muhammadAzam DESC
    LIMIT 5
");

// helper hari indonesia
function hariIndonesia($hari) {
    $map = [
        "Sunday" => "Minggu",
        "Monday" => "Senin",
        "Tuesday" => "Selasa",
        "Wednesday" => "Rabu",
        "Thursday" => "Kamis",
        "Friday" => "Jumat",
        "Saturday" => "Sabtu"
    ];
    return $map[$hari] ?? $hari;
}

$page = "dashboard";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Murid</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter','Segoe UI',sans-serif;}
body{background:#f4f6fb;color:#333;}

.navbar{
    background:linear-gradient(135deg,#1e3c72,#2a5298);
    color:#fff;
    padding:16px 34px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 6px 18px rgba(0,0,0,0.15);
    position:sticky;
    top:0;
    z-index:99;
}

.navbar .logo{font-size:18px;font-weight:700;}
.navbar .menu{display:flex;align-items:center;gap:14px;flex-wrap:wrap;}

.navbar .menu a{
    color:#fff;text-decoration:none;font-size:14px;
    padding:7px 12px;border-radius:12px;transition:0.25s;
}
.navbar .menu a:hover{background:rgba(255,255,255,0.15);}

/* ACTIVE MENU */
.navbar .menu a.active{
    background:#fff;
    color:#1e3c72;
    font-weight:800;
}

.navbar .menu a.logout{
    padding:7px 16px;border:1px solid #f1c40f;border-radius:20px;
    background:rgba(255,255,255,0.12);color:#f1c40f;font-weight:700;
}
.navbar .menu a.logout:hover{background:#f1c40f;color:#1e3c72;}

.main{padding:32px 40px;animation:fadeIn 0.6s ease;}

.header{
    background:#fff;
    border-radius:18px;
    padding:26px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
}

.header h2{color:#1e3c72;font-size:24px;margin-bottom:8px;}
.header p{color:#666;font-size:14px;line-height:1.6;}
.badge{
    display:inline-block;margin-top:10px;
    padding:6px 14px;border-radius:999px;
    font-size:12px;font-weight:700;
    background:#eaf1ff;color:#1e3c72;
    border:1px solid rgba(30,60,114,0.18);
}

.stats{
    margin-top:22px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:16px;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
    border:1px solid rgba(0,0,0,0.03);
    position:relative;
    overflow:hidden;
}

.card::before{
    content:"";
    position:absolute;
    top:-35px;
    right:-35px;
    width:110px;
    height:110px;
    border-radius:50%;
    background:rgba(30,60,114,0.08);
}

.card h3{font-size:26px;color:#1e3c72;margin-bottom:6px;}
.card p{font-size:13px;color:#666;}
.small{margin-top:10px;font-size:12px;color:#888;}

.grid2{
    margin-top:22px;
    display:grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap:16px;
}

.table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    font-size:13px;
}

.table th, .table td{
    padding:10px 12px;
    border-bottom:1px solid #eee;
    text-align:left;
}

.table th{color:#1e3c72;font-weight:700;}

.status{
    font-weight:700;
    padding:4px 10px;
    border-radius:999px;
    display:inline-block;
    font-size:12px;
}

.hadir{background:#eafff1;color:#1d8f4e;border:1px solid #bff5d2;}
.izin{background:#fff6e5;color:#b97700;border:1px solid #ffe2a8;}
.sakit{background:#e9f2ff;color:#1e3c72;border:1px solid #b8d4ff;}
.alpha{background:#ffeaea;color:#c0392b;border:1px solid #ffbcbc;}

.footer{
    margin-top:40px;
    text-align:center;
    font-size:13px;
    color:#777;
    padding-bottom:25px;
}

@keyframes fadeIn{
    from{opacity:0;transform:translateY(15px);}
    to{opacity:1;transform:translateY(0);}
}

@media(max-width:900px){
    .grid2{grid-template-columns:1fr;}
}
@media(max-width:768px){
    .main{padding:24px 18px;}
    .header{flex-direction:column;align-items:flex-start;}
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">🎓 Sistem Akademik RFID</div>
    <div class="menu">
        <a href="dashboard_muhammadAzam.php" class="<?= $page=="dashboard" ? "active" : "" ?>">Beranda</a>
        <a href="biodata_muhammadAzam.php" class="<?= $page=="biodata" ? "active" : "" ?>">Biodata</a>
        <a href="nilai_muhammadAzam.php" class="<?= $page=="nilai" ? "active" : "" ?>">Nilai</a>
        <a href="kehadiran_muhammadAzam.php" class="<?= $page=="kehadiran" ? "active" : "" ?>">Kehadiran</a>
        <a href="../login_muhammadAzam/logout_muhammadAzam.php" class="logout">Logout</a>
    </div>
</div>

<div class="main">

    <div class="header">
        <div>
            <h2>Halo, <?= htmlspecialchars($nama); ?> 👋</h2>
            <p>
                Selamat datang kembali.<br>
                Semua data di halaman ini diambil dari database berdasarkan RFID kamu.
            </p>
            <div class="badge">
                Kelas: <?= htmlspecialchars($kelas); ?> • ID Murid: <?= htmlspecialchars($id_murid); ?>
            </div>
        </div>

        <div style="text-align:right;color:#666;font-size:13px;">
            <div style="font-weight:700;color:#1e3c72;">
                <?= hariIndonesia(date("l")) . ", " . date("d M Y"); ?>
            </div>
            <div><?= date("H:i"); ?> WIB</div>
        </div>
    </div>

    <div class="stats">
        <div class="card">
            <h3><?= $totalAbsen > 0 ? $persenHadir."%" : "-"; ?></h3>
            <p>Persentase Kehadiran</p>
            <div class="small">
                <?= $totalAbsen > 0 ? "Hadir: $hadir • Total: $totalAbsen" : "Belum ada data kehadiran"; ?>
            </div>
        </div>

        <div class="card">
            <h3><?= $totalNilai > 0 ? $rataNilai : "-"; ?></h3>
            <p>Rata-rata Nilai</p>
            <div class="small">
                <?= $totalNilai > 0 ? "Total data nilai: $totalNilai" : "Belum ada data nilai"; ?>
            </div>
        </div>

        <div class="card">
            <h3><?= $totalMapel > 0 ? $totalMapel : "-"; ?></h3>
            <p>Total Mata Pelajaran</p>
            <div class="small">Diambil dari tabel tb_mapel</div>
        </div>

        <div class="card">
            <h3><?= $totalAbsen > 0 ? $alpha : "-"; ?></h3>
            <p>Total Alpha</p>
            <div class="small">
                <?= $totalAbsen > 0 ? "Izin: $izin • Sakit: $sakit" : "Belum ada data absensi"; ?>
            </div>
        </div>
    </div>

    <div class="grid2">

        <div class="card">
            <h3 style="font-size:18px;margin-bottom:6px;">📌 Menu Cepat</h3>
            <p>Silakan pilih menu untuk melihat data lengkap.</p>

            <div style="margin-top:14px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
                <a href="biodata_muhammadAzam.php" style="text-decoration:none;">
                    <div class="card" style="box-shadow:none;border:1px solid #eee;">
                        <h3 style="font-size:16px;">🧑‍🎓 Biodata</h3>
                        <p>Lihat data diri siswa</p>
                    </div>
                </a>

                <a href="nilai_muhammadAzam.php" style="text-decoration:none;">
                    <div class="card" style="box-shadow:none;border:1px solid #eee;">
                        <h3 style="font-size:16px;">📊 Nilai</h3>
                        <p>Lihat nilai per mapel & semester</p>
                    </div>
                </a>

                <a href="kehadiran_muhammadAzam.php" style="text-decoration:none;">
                    <div class="card" style="box-shadow:none;border:1px solid #eee;">
                        <h3 style="font-size:16px;">🕒 Kehadiran</h3>
                        <p>Rekap absensi siswa</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size:18px;margin-bottom:6px;">🗓️ Absensi Terakhir</h3>
            <p>5 data absensi terbaru dari database.</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($qLastAbsen) > 0) { ?>
                    <?php while($row = mysqli_fetch_assoc($qLastAbsen)) { 
                        $st = strtolower($row['status_muhammadAzam']);
                    ?>
                    <tr>
                        <td><?= date("d M Y", strtotime($row['tanggal_muhammadAzam'])); ?></td>
                        <td>
                            <span class="status <?= $st; ?>">
                                <?= htmlspecialchars($row['status_muhammadAzam']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="2" style="color:#888;">Belum ada data absensi.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="footer">
        © <?= date("Y"); ?> Sistem Akademik RFID • All Rights Reserved
    </div>

</div>

</body>
</html>
