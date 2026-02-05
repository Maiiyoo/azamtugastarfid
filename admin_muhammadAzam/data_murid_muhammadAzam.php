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

// =====================
// FILTER & SEARCH
// =====================
$search = $_GET['search'] ?? '';
$kelas  = $_GET['kelas'] ?? '';

$search_safe = mysqli_real_escape_string($koneksi_muhammadAzam, $search);
$kelas_safe  = mysqli_real_escape_string($koneksi_muhammadAzam, $kelas);

$where = "WHERE 1=1";
if (!empty($search_safe)) {
    $where .= " AND (
        nama_lengkap_muhammadAzam LIKE '%$search_safe%' OR
        nis_muhammadAzam LIKE '%$search_safe%' OR
        rfid_muhammadAzam LIKE '%$search_safe%'
    )";
}
if (!empty($kelas_safe)) {
    $where .= " AND kelas_muhammadAzam = '$kelas_safe'";
}

// =====================
// AMBIL DATA MURID
// =====================
$qMurid = mysqli_query($koneksi_muhammadAzam, "
    SELECT 
        id_murid_muhammadAzam,
        nis_muhammadAzam,
        nama_lengkap_muhammadAzam,
        kelas_muhammadAzam,
        rfid_muhammadAzam,
        tahun_ajaran_muhammadAzam
    FROM tb_murid_muhammadAzam
    $where
    ORDER BY nama_lengkap_muhammadAzam ASC
");

// =====================
// AMBIL LIST KELAS UNTUK FILTER
// =====================
$qKelas = mysqli_query($koneksi_muhammadAzam, "
    SELECT DISTINCT kelas_muhammadAzam 
    FROM tb_murid_muhammadAzam
    ORDER BY kelas_muhammadAzam ASC
");

function aman($val){
    return htmlspecialchars($val ?? "-", ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Murid</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter','Segoe UI',sans-serif;}
body{background:#f4f6fb;color:#333;}
.wrapper{display:flex;min-height:100vh;}

/* SIDEBAR */
.sidebar{width:260px;background:linear-gradient(180deg,#1e3c72,#2a5298);color:#fff;padding:22px 18px;position:sticky;top:0;height:100vh;overflow:auto;}
.brand{display:flex;align-items:center;gap:10px;font-weight:900;font-size:16px;margin-bottom:20px;}
.brand .icon{width:42px;height:42px;border-radius:14px;background:rgba(255,255,255,0.16);display:flex;align-items:center;justify-content:center;font-size:18px;}
.brand small{display:block;font-size:12px;opacity:0.85;font-weight:600;margin-top:2px;}
.nav{display:flex;flex-direction:column;gap:8px;}
.nav a{color:#fff;text-decoration:none;font-size:14px;padding:11px 12px;border-radius:14px;transition:0.25s;display:flex;align-items:center;gap:10px;opacity:0.95;font-weight:600;}
.nav a:hover{background:rgba(255,255,255,0.14);transform:translateX(3px);}
.nav a.active{background:#fff;color:#1e3c72;font-weight:900;box-shadow:0 12px 22px rgba(0,0,0,0.15);}
.logout{margin-top:18px;border-top:1px solid rgba(255,255,255,0.2);padding-top:18px;}
.logout a{background:rgba(255,255,255,0.12);border:1px solid rgba(241,196,15,0.7);color:#f1c40f;font-weight:900;justify-content:center;}
.logout a:hover{background:#f1c40f;color:#1e3c72;transform:none;}

/* MAIN */
.main{flex:1;padding:26px 28px;}

/* TOPBAR */
.topbar{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:18px;}
.topbar h2{font-size:24px;color:#1e3c72;font-weight:900;}
.topbar p{font-size:13px;color:#666;margin-top:6px;line-height:1.6;}

/* TOOLBAR */
.toolbar{margin-top:14px;background:#fff;border-radius:18px;padding:18px;box-shadow:0 12px 26px rgba(0,0,0,0.08);display:flex;flex-wrap:wrap;gap:12px;align-items:center;justify-content:space-between;}
.toolbar form{display:flex;flex-wrap:wrap;gap:10px;align-items:center;}
.toolbar input, .toolbar select{padding:10px 12px;border:1px solid #ddd;border-radius:12px;font-size:13px;outline:none;min-width:220px;}
.toolbar button{padding:10px 16px;border:none;border-radius:12px;font-size:13px;font-weight:900;cursor:pointer;background:#1e3c72;color:#fff;transition:0.2s;}
.toolbar button:hover{background:#16305d;}
.btn-add{padding:10px 16px;border-radius:12px;background:#27ae60;color:#fff;text-decoration:none;font-size:13px;font-weight:900;transition:0.2s;}
.btn-add:hover{background:#219150;}

/* TABLE */
.table-box{margin-top:16px;background:#fff;border-radius:18px;box-shadow:0 12px 26px rgba(0,0,0,0.08);overflow:hidden;}
table{width:100%;border-collapse:collapse;}
thead{background:linear-gradient(135deg,#1e3c72,#2a5298);color:#fff;}
th, td{padding:14px 16px;text-align:left;font-size:13px;border-bottom:1px solid #eee;}
tbody tr:nth-child(even){background:#f9fafc;}
tbody tr:hover{background:#eef2ff;}
.badge-kelas{display:inline-block;padding:5px 12px;border-radius:999px;font-size:12px;font-weight:900;background:#eaf1ff;border:1px solid rgba(30,60,114,0.18);color:#1e3c72;}
.btn-edit{padding:7px 12px;background:#f1c40f;color:#1e3c72;font-weight:900;border-radius:12px;text-decoration:none;font-size:12px;display:inline-block;}
.btn-edit:hover{background:#d4ac0d;}
.empty{text-align:center;color:#777;font-size:14px;padding:20px;}
.footer{margin-top:26px;text-align:center;font-size:13px;color:#777;padding-bottom:12px;}

/* RESPONSIVE */
@media(max-width:900px){
    .sidebar{display:none;}
    .main{padding:20px 18px;}
    .toolbar input,.toolbar select{min-width:100%;}
    .toolbar{align-items:stretch;}
    .toolbar form{width:100%;}
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
            <a href="dashboard_admin_muhammadAzam.php">🏠 Dashboard</a>
            <a href="data_guru_muhammadAzam.php">👨‍🏫 Data Guru</a>
            <a href="data_mapel_muhammadAzam.php">📚 Data Mapel</a>
            <a href="data_murid_muhammadAzam.php"  class="active">👥 Data Siswa</a>
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
                <h2>👥 Data Murid</h2>
                <p>Kelola semua data murid dengan cepat: search, filter kelas, edit.</p>
            </div>
        </div>

        <!-- TOOLBAR -->
        <div class="toolbar">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari nama / NIS / RFID..." value="<?= aman($search); ?>">
                <select name="kelas">
                    <option value="">-- Semua Kelas --</option>
                    <?php while($k = mysqli_fetch_assoc($qKelas)): 
                        $kl = $k['kelas_muhammadAzam'];
                        $sel = ($kelas == $kl) ? "selected" : "";
                    ?>
                        <option value="<?= aman($kl); ?>" <?= $sel; ?>><?= aman($kl); ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Terapkan</button>
            </form>
            <a href="input_murid_muhammadAzam.php" class="btn-add">➕ Tambah Murid</a>
        </div>

        <!-- TABLE -->
        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th style="width:70px;">No</th>
                        <th>NIS</th>
                        <th>Nama Lengkap</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($qMurid) == 0): ?>
                        <tr><td colspan="6" class="empty">Data murid tidak ditemukan.</td></tr>
                    <?php else: $no=1; while($row=mysqli_fetch_assoc($qMurid)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= aman($row['nis_muhammadAzam']); ?></td>
                            <td><?= aman($row['nama_lengkap_muhammadAzam']); ?></td>
                            <td><span class="badge-kelas"><?= aman($row['kelas_muhammadAzam']); ?></span></td>
                            <td><?= aman($row['tahun_ajaran_muhammadAzam']); ?></td>
                            <td>
                                <a class="btn-edit" href="edit_murid_muhammadAzam.php?id=<?= aman($row['id_murid_muhammadAzam']); ?>">✏️ Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer">© <?= date("Y"); ?> Sistem Akademik RFID • Data Murid</div>
    </main>
</div>
</body>
</html>
