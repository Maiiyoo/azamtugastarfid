<?php
include __DIR__ . "/../config_muhammadAzam/session_muhammadAzam.php";
include __DIR__ . "/../config_muhammadAzam/koneksi_muhammadAzam.php";

// =====================
// CEK ROLE ADMIN
// =====================
if (!isset($_SESSION['role_muhammadAzam']) || $_SESSION['role_muhammadAzam'] != 'admin') {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}

date_default_timezone_set("Asia/Jakarta");

// =====================
// HITUNG STATISTIK
// =====================
function getCount($koneksi, $table) {
    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM $table");
    $data = mysqli_fetch_assoc($res);
    return $data['total'] ?? 0;
}

$totalMurid  = getCount($koneksi_muhammadAzam, 'tb_murid_muhammadAzam');
$totalMapel  = getCount($koneksi_muhammadAzam, 'tb_mapel_muhammadAzam');
$totalNilai  = getCount($koneksi_muhammadAzam, 'tb_nilai_muhammadAzam');
$totalGuru   = getCount($koneksi_muhammadAzam, 'tb_guru_muhammadAzam'); // Updated to count teachers

function aman($val){
    return htmlspecialchars($val ?? "0", ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter','Segoe UI',sans-serif;}
body{background:#f4f6fb;color:#333;}
.wrapper{display:flex;min-height:100vh;}

/* SIDEBAR */
.sidebar{width:260px;background:linear-gradient(180deg,#1e3c72,#2a5298);color:#fff;padding:22px 18px;position:sticky;top:0;height:100vh;overflow:auto;}
.brand{display:flex;align-items:center;gap:10px;font-weight:900;font-size:16px;margin-bottom:20px;}
.brand .icon{width:42px;height:42px;border-radius:14px;background:rgba(255,255,255,0.16);display:flex;align-items:center;justify-content:center;font-size:18px;}
.brand small{display:block;font-size:12px;opacity:0.85;font-weight:600;margin-top:2px;}
.nav{display:flex;flex-direction:column;gap:8px;margin-top:10px;}
.nav a{color:#fff;text-decoration:none;font-size:14px;padding:11px 12px;border-radius:14px;transition:0.25s;display:flex;align-items:center;gap:10px;opacity:0.95;font-weight:600;}
.nav a:hover{background:rgba(255,255,255,0.14);transform:translateX(3px);}
.nav a.active{background:#fff;color:#1e3c72;font-weight:900;box-shadow:0 12px 22px rgba(0,0,0,0.15);}
.sidebar .divider{margin:16px 0;height:1px;background:rgba(255,255,255,0.18);}
.logout a{background:rgba(255,255,255,0.12);border:1px solid rgba(241,196,15,0.7);color:#f1c40f;font-weight:900;justify-content:center;}
.logout a:hover{background:#f1c40f;color:#1e3c72;transform:none;}

/* MAIN */
.main{flex:1;padding:26px 28px;}
.topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:18px;}
.topbar h2{font-size:24px;color:#1e3c72;font-weight:900;}
.topbar p{font-size:13px;color:#666;margin-top:6px;line-height:1.6;}

/* HERO */
.hero{background:#fff;border-radius:20px;padding:22px;box-shadow:0 14px 34px rgba(0,0,0,0.08);display:flex;gap:22px;align-items:center;justify-content:space-between;overflow:hidden;margin-bottom:18px;border:1px solid rgba(0,0,0,0.04);animation:fadeIn 0.6s ease;}
.hero-text{max-width:680px;}
.hero-text h3{color:#1e3c72;font-size:20px;font-weight:900;margin-bottom:8px;}
.hero-text p{color:#555;font-size:14px;line-height:1.7;}
.hero-text .badge{display:inline-block;margin-top:12px;padding:7px 14px;border-radius:999px;background:#eaf1ff;border:1px solid rgba(30,60,114,0.18);color:#1e3c72;font-size:12px;font-weight:900;}
.hero-img{width:180px;min-width:180px;height:170px;border-radius:20px;overflow:hidden;background:#eaf1ff;border:1px solid rgba(0,0,0,0.05);box-shadow:0 12px 28px rgba(0,0,0,0.10);}
.hero-img img{width:100%;height:100%;object-fit:cover;}

/* STATS */
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;margin-bottom:18px;}
.stat{background:#fff;border-radius:18px;padding:18px;box-shadow:0 12px 26px rgba(0,0,0,0.07);transition:0.25s;border:1px solid rgba(0,0,0,0.04);position:relative;overflow:hidden;}
.stat::before{content:"";position:absolute;top:-35px;right:-35px;width:120px;height:120px;border-radius:50%;background:rgba(30,60,114,0.08);}
.stat:hover{transform:translateY(-4px);box-shadow:0 18px 40px rgba(0,0,0,0.12);}
.stat .title{color:#777;font-size:13px;font-weight:700;}
.stat .num{margin-top:8px;font-size:28px;font-weight:900;color:#1e3c72;}
.stat .desc{margin-top:6px;font-size:12px;color:#666;}

/* QUICK MENU */
.section-title{font-weight:900;color:#1e3c72;margin-bottom:12px;font-size:15px;}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px;}
.card{background:#fff;border-radius:18px;padding:20px;box-shadow:0 12px 26px rgba(0,0,0,0.07);transition:0.25s;cursor:pointer;border:1px solid rgba(0,0,0,0.04);animation:fadeIn 0.6s ease;}
.card:hover{transform:translateY(-5px);box-shadow:0 20px 44px rgba(0,0,0,0.12);}
.card h4{color:#1e3c72;margin-bottom:7px;font-size:15px;font-weight:900;}
.card p{color:#666;font-size:13px;line-height:1.6;}
.footer{margin-top:26px;text-align:center;font-size:13px;color:#777;padding-bottom:12px;}
@keyframes fadeIn{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}

/* RESPONSIVE */
@media(max-width:900px){
    .sidebar{display:none;}
    .main{padding:20px 18px;}
    .hero{flex-direction:column;align-items:flex-start;}
    .hero-img{width:100%;min-width:100%;height:200px;}
    .clock{min-width:unset;width:100%;text-align:left;}
    .topbar{flex-direction:column;align-items:flex-start;}
}
</style>
</head>
<body>
<div class="wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand">
            <div class="icon">🛠️</div>
            <div>
                Admin Panel RFID
                <small>Kelola data sekolah</small>
            </div>
        </div>

        <nav class="nav">
            <a href="dashboard_admin_muhammadAzam.php" class="active">🏠 Dashboard</a>
            <a href="data_guru_muhammadAzam.php">👨‍🏫 Data Guru</a>
            <a href="data_mapel_muhammadAzam.php">📚 Data Mapel</a>
            <a href="data_murid_muhammadAzam.php">👥 Data Siswa</a>
            <a href="data_nilai_muhammadAzam.php">📝 Data Nilai</a>
        </nav>

        <div class="divider"></div>

        <div class="nav logout">
            <a href="../login_muhammadAzam/logout_muhammadAzam.php">🚪 Logout</a>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <h2>Dashboard Admin</h2>
                <p>Kelola murid, nilai, kehadiran, dan cetak rapor massal secara cepat, rapi, dan aman.</p>
            </div>

            <div class="clock">
                <div class="date" id="date"><?= date("l, d M Y"); ?></div>
                <div class="time" id="time"><?= date("H:i:s"); ?> WIB</div>
            </div>
        </div>

        <!-- HERO -->
        <section class="hero">
            <div class="hero-text">
                <h3>Selamat Datang Admin 👋</h3>
                <p>Dashboard ini dibuat untuk mempermudah pengelolaan sistem akademik RFID. Kamu bisa input murid baru, input nilai per semester, input kehadiran, dan cetak rapor massal per kelas.</p>
                <div class="badge">🚀 Sistem siap dipakai</div>
            </div>
            <div class="hero-img">
                <img src="../assets_muhammadAzam/img/admin_sekolah.jpg" alt="Dashboard Admin">
            </div>
        </section>

        <!-- STATS -->
        <section class="stats">
            <div class="stat">
                <div class="title">Total Murid</div>
                <div class="num"><?= aman($totalMurid); ?></div>
                <div class="desc">Jumlah murid yang tersimpan</div>
            </div>
            <div class="stat">
                <div class="title">Total Mapel</div>
                <div class="num"><?= aman($totalMapel); ?></div>
                <div class="desc">Jumlah mata pelajaran</div>
            </div>
            <div class="stat">
                <div class="title">Data Nilai</div>
                <div class="num"><?= aman($totalNilai); ?></div>
                <div class="desc">Total record nilai tersimpan</div>
            </div>
            <div class="stat">
                <div class="title">Total Guru</div> <!-- Updated -->
                <div class="num"><?= aman($totalGuru); ?></div> <!-- Updated -->
                <div class="desc">Jumlah guru yang tersimpan</div> <!-- Updated -->
            </div>
        </section>

        <!-- QUICK MENU -->
        <div class="section-title">⚡ Menu Cepat</div>
        <section class="grid">
            <div class="card" onclick="location.href='data_murid_muhammadAzam.php'">
                <h4>👥 Data Murid</h4>
                <p>Lihat, edit, atau hapus biodata murid secara lengkap.</p>
            </div>
            <div class="card" onclick="location.href='input_murid_muhammadAzam.php'">
                <h4>➕ Tambah Murid</h4>
                <p>Tambahkan murid baru, sistem akan membuat ID otomatis.</p>
            </div>
            <div class="card" onclick="location.href='input_nilai_muhammadAzam.php'">
                <h4>📝 Input Nilai</h4>
                <p>Input nilai per mapel, kelas, semester, dan tahun ajaran.</p>
            </div>
            <div class="card" onclick="location.href='input_kehadiran_muhammadAzam.php'">
                <h4>📅 Input Kehadiran</h4>
                <p>Catat hadir, sakit, izin, dan alpha untuk murid.</p>
            </div>
            <div class="card" onclick="location.href='cetak_rapor_massal_muhammadAzam.php'">
                <h4>🖨️ Cetak Rapor</h4>
                <p>Cetak rapor massal per kelas dengan format PDF.</p>
            </div>
        </section>

        <div class="footer">© <?= date("Y"); ?> Sistem Akademik RFID • Dashboard Admin</div>
    </main>
</div>

<script>
// Clock Real-Time
function updateClock() {
    const now = new Date();
    const time = now.toLocaleTimeString('id-ID', {hour12:false});
    document.getElementById('time').textContent = time + ' WIB';
    requestAnimationFrame(updateClock);
}
updateClock();
</script>
</body>
</html>
