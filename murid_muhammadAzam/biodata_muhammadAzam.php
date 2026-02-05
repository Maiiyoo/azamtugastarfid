<?php
include "../config_muhammadAzam/session_muhammadAzam.php";
include "../config_muhammadAzam/koneksi_muhammadAzam.php";

// =====================
// CEK LOGIN MURID
// =====================
if (!isset($_SESSION['rfid_muhammadAzam']) || $_SESSION['role_muhammadAzam'] != "murid") {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

$rfid_muhammadAzam = mysqli_real_escape_string($koneksi_muhammadAzam, $_SESSION['rfid_muhammadAzam']);

// =====================
// AMBIL DATA MURID
// =====================
$query_muhammadAzam = mysqli_query(
    $koneksi_muhammadAzam,
    "SELECT * FROM tb_murid_muhammadAzam 
     WHERE rfid_muhammadAzam = '$rfid_muhammadAzam'
     LIMIT 1"
);

$data_muhammadAzam = mysqli_fetch_assoc($query_muhammadAzam);

if (!$data_muhammadAzam) {
    die("Data murid tidak ditemukan. Pastikan RFID murid ada di tb_murid_muhammadAzam.");
}

// helper aman
function aman($val) {
    return (!empty($val)) ? htmlspecialchars($val) : "-";
}

// =====================
// NAV ACTIVE OTOMATIS
// =====================
$currentPage = basename($_SERVER['PHP_SELF']);

function navActive($file, $currentPage) {
    return ($file == $currentPage) ? "active" : "";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Biodata Murid</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter','Segoe UI',sans-serif;}
body{background:#f4f6fb;color:#333;}

/* ================= NAVBAR ================= */
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
    color:#fff;
    text-decoration:none;
    font-size:14px;
    padding:7px 12px;
    border-radius:12px;
    transition:0.25s;
}

.navbar .menu a:hover{
    background:rgba(255,255,255,0.20);
}

/* ACTIVE PUTIH */
.navbar .menu a.active{
    background:#fff;
    color:#1e3c72;
    font-weight:700;
    box-shadow:0 6px 14px rgba(0,0,0,0.15);
}

/* LOGOUT */
.navbar .menu a.logout{
    padding:7px 16px;
    border:1px solid #f1c40f;
    border-radius:20px;
    background:rgba(255,255,255,0.12);
    color:#f1c40f;
    font-weight:700;
}

.navbar .menu a.logout:hover{
    background:#f1c40f;
    color:#1e3c72;
}

/* ================= MAIN ================= */
.main{padding:32px 40px;animation:fadeIn 0.6s ease;}

/* ================= HEADER CARD ================= */
.header{
    background:#fff;
    border-radius:18px;
    padding:26px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    margin-bottom:18px;
}

.header h2{color:#1e3c72;font-size:24px;margin-bottom:8px;}
.header p{color:#666;font-size:14px;line-height:1.6;}

.badge{
    display:inline-block;
    margin-top:10px;
    padding:6px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    background:#eaf1ff;
    color:#1e3c72;
    border:1px solid rgba(30,60,114,0.18);
}

/* ================= CARD ================= */
.card{
    background:#fff;
    border-radius:18px;
    padding:26px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    border:1px solid rgba(0,0,0,0.03);
}

/* ================= BIODATA GRID ================= */
.biodata-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px 35px;
    margin-top:10px;
}

.biodata-item{
    border-bottom:1px solid #eee;
    padding-bottom:12px;
}

.biodata-item span{
    display:block;
    font-size:13px;
    color:#777;
    margin-bottom:5px;
}

.biodata-item strong{
    font-size:14.5px;
    color:#1e3c72;
    font-weight:700;
}

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

/* ================= RESPONSIVE ================= */
@media(max-width:900px){
    .biodata-grid{grid-template-columns:1fr;}
}
@media(max-width:768px){
    .main{padding:24px 18px;}
    .header{flex-direction:column;align-items:flex-start;}
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🎓 Sistem Akademik RFID</div>
    <div class="menu">
        <a href="dashboard_muhammadAzam.php" class="<?= navActive('dashboard_muhammadAzam.php', $currentPage); ?>">Beranda</a>
        <a href="biodata_muhammadAzam.php" class="<?= navActive('biodata_muhammadAzam.php', $currentPage); ?>">Biodata</a>
        <a href="nilai_muhammadAzam.php" class="<?= navActive('nilai_muhammadAzam.php', $currentPage); ?>">Nilai</a>
        <a href="kehadiran_muhammadAzam.php" class="<?= navActive('kehadiran_muhammadAzam.php', $currentPage); ?>">Kehadiran</a>
        <a href="../login_muhammadAzam/logout_muhammadAzam.php" class="logout">Logout</a>
    </div>
</div>

<div class="main">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h2>📄 Biodata Murid</h2>
            <p>Berikut adalah biodata lengkap murid berdasarkan RFID yang sedang login.</p>
            <div class="badge">
                <?= aman($data_muhammadAzam['nama_lengkap_muhammadAzam']); ?> • 
                Kelas <?= aman($data_muhammadAzam['kelas_muhammadAzam']); ?>
            </div>
        </div>

        <div style="text-align:right;color:#666;font-size:13px;">
            <div style="font-weight:700;color:#1e3c72;"><?= date("l, d M Y"); ?></div>
            <div><?= date("H:i"); ?> WIB</div>
        </div>
    </div>

    <!-- CARD BIODATA -->
    <div class="card">
        <div class="biodata-grid">

            <div class="biodata-item"><span>NIS</span><strong><?= aman($data_muhammadAzam['nis_muhammadAzam']); ?></strong></div>
            <div class="biodata-item"><span>Nama Lengkap</span><strong><?= aman($data_muhammadAzam['nama_lengkap_muhammadAzam']); ?></strong></div>

            <div class="biodata-item"><span>RFID</span><strong><?= aman($data_muhammadAzam['rfid_muhammadAzam']); ?></strong></div>
            <div class="biodata-item"><span>Kelas</span><strong><?= aman($data_muhammadAzam['kelas_muhammadAzam']); ?></strong></div>

            <div class="biodata-item"><span>Tempat Lahir</span><strong><?= aman($data_muhammadAzam['tempat_lahir_muhammadAzam']); ?></strong></div>
            <div class="biodata-item">
                <span>Tanggal Lahir</span>
                <strong>
                    <?= !empty($data_muhammadAzam['tanggal_lahir_muhammadAzam']) 
                        ? date("d M Y", strtotime($data_muhammadAzam['tanggal_lahir_muhammadAzam'])) 
                        : "-"; ?>
                </strong>
            </div>

            <div class="biodata-item"><span>Jenis Kelamin</span><strong><?= aman($data_muhammadAzam['jenis_kelamin_muhammadAzam']); ?></strong></div>
            <div class="biodata-item"><span>Agama</span><strong><?= aman($data_muhammadAzam['agama_muhammadAzam']); ?></strong></div>

            <div class="biodata-item"><span>Alamat</span><strong><?= aman($data_muhammadAzam['alamat_muhammadAzam']); ?></strong></div>
            <div class="biodata-item"><span>No HP Wali</span><strong><?= aman($data_muhammadAzam['no_hp_ortu_muhammadAzam']); ?></strong></div>

            <div class="biodata-item"><span>Nama Ayah</span><strong><?= aman($data_muhammadAzam['nama_ayah_muhammadAzam']); ?></strong></div>
            <div class="biodata-item"><span>Nama Ibu</span><strong><?= aman($data_muhammadAzam['nama_ibu_muhammadAzam']); ?></strong></div>

            <div class="biodata-item"><span>Tahun Ajaran</span><strong><?= aman($data_muhammadAzam['tahun_ajaran_muhammadAzam']); ?></strong></div>
            <div class="biodata-item"><span>Status</span><strong>Aktif</strong></div>

        </div>
    </div>

    <div class="footer">
        © <?= date("Y"); ?> Sistem Akademik RFID • All Rights Reserved
    </div>

</div>

</body>
</html>
