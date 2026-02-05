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
    SELECT *
    FROM tb_murid_muhammadAzam
    WHERE rfid_muhammadAzam = '$rfid'
    LIMIT 1
");

$murid = mysqli_fetch_assoc($qMurid);

if (!$murid) {
    die("Data murid tidak ditemukan. Pastikan RFID murid ada di tb_murid_muhammadAzam.");
}

$id_murid = $murid['id_murid_muhammadAzam'];
$nama     = $murid['nama_lengkap_muhammadAzam'] ?? "Siswa";

// =====================
// FILTER (GET)
// =====================
$kelas    = $_GET['kelas'] ?? '';
$semester = $_GET['semester'] ?? '';
$tahun    = $_GET['tahun'] ?? '';

// =====================
// LIST FILTER KELAS DARI DB (KHUSUS MURID INI)
// =====================
$qKelas = mysqli_query($koneksi_muhammadAzam, "
    SELECT DISTINCT kelas_muhammadAzam
    FROM tb_kehadiran_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
    AND kelas_muhammadAzam IS NOT NULL AND kelas_muhammadAzam != ''
    ORDER BY kelas_muhammadAzam ASC
");

// =====================
// LIST TAHUN AJARAN DARI DB (KHUSUS MURID INI)
// =====================
$qTahun = mysqli_query($koneksi_muhammadAzam, "
    SELECT DISTINCT tahun_ajaran_muhammadAzam
    FROM tb_kehadiran_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
    AND tahun_ajaran_muhammadAzam IS NOT NULL AND tahun_ajaran_muhammadAzam != ''
    ORDER BY tahun_ajaran_muhammadAzam DESC
");

// =====================
// QUERY TOTAL KEHADIRAN
// =====================
$sqlCount = "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status_muhammadAzam='Hadir' THEN 1 ELSE 0 END) AS hadir,
        SUM(CASE WHEN status_muhammadAzam='Izin' THEN 1 ELSE 0 END) AS izin,
        SUM(CASE WHEN status_muhammadAzam='Sakit' THEN 1 ELSE 0 END) AS sakit,
        SUM(CASE WHEN status_muhammadAzam='Alpha' THEN 1 ELSE 0 END) AS alpha
    FROM tb_kehadiran_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
";

if ($kelas != "")    $sqlCount .= " AND kelas_muhammadAzam = '$kelas' ";
if ($semester != "") $sqlCount .= " AND semester_muhammadAzam = '$semester' ";
if ($tahun != "")    $sqlCount .= " AND tahun_ajaran_muhammadAzam = '$tahun' ";

$qCount = mysqli_query($koneksi_muhammadAzam, $sqlCount);
$abs = mysqli_fetch_assoc($qCount);

$total = (int)($abs['total'] ?? 0);
$hadir = (int)($abs['hadir'] ?? 0);
$izin  = (int)($abs['izin'] ?? 0);
$sakit = (int)($abs['sakit'] ?? 0);
$alpha = (int)($abs['alpha'] ?? 0);

$pHadir = ($total > 0) ? round(($hadir / $total) * 100) : 0;
$pIzin  = ($total > 0) ? round(($izin / $total) * 100) : 0;
$pSakit = ($total > 0) ? round(($sakit / $total) * 100) : 0;
$pAlpha = ($total > 0) ? round(($alpha / $total) * 100) : 0;

// =====================
// LIST DATA DETAIL
// =====================
$sqlList = "
    SELECT tanggal_muhammadAzam, status_muhammadAzam, kelas_muhammadAzam, semester_muhammadAzam, tahun_ajaran_muhammadAzam
    FROM tb_kehadiran_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
";

if ($kelas != "")    $sqlList .= " AND kelas_muhammadAzam = '$kelas' ";
if ($semester != "") $sqlList .= " AND semester_muhammadAzam = '$semester' ";
if ($tahun != "")    $sqlList .= " AND tahun_ajaran_muhammadAzam = '$tahun' ";

$sqlList .= " ORDER BY tanggal_muhammadAzam DESC";

$qList = mysqli_query($koneksi_muhammadAzam, $sqlList);

// helper status class
function statusClass($st) {
    $st = strtolower(trim($st));
    if ($st == "hadir") return "hadir";
    if ($st == "izin") return "izin";
    if ($st == "sakit") return "sakit";
    return "alpha";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kehadiran Murid</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter','Segoe UI',sans-serif;}
body{background:#f4f6fb;color:#333;}

/* NAVBAR */
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
.navbar .menu{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.navbar .menu a{
    color:#fff;text-decoration:none;font-size:14px;
    padding:8px 14px;border-radius:14px;transition:0.25s;
}
.navbar .menu a:hover{background:rgba(255,255,255,0.15);}

/* MENU AKTIF */
.navbar .menu a.active{
    background:#fff;
    color:#1e3c72;
    font-weight:800;
}

.navbar .menu a.logout{
    padding:8px 16px;border:1px solid #f1c40f;border-radius:20px;
    background:rgba(255,255,255,0.12);color:#f1c40f;font-weight:800;
}
.navbar .menu a.logout:hover{background:#f1c40f;color:#1e3c72;}

.main{padding:32px 40px;animation:fadeIn 0.5s ease;}
h2{color:#1e3c72;margin-bottom:8px;}
.sub{color:#666;margin-bottom:18px;font-size:14px;}

.filter-box{
    background:#fff;
    padding:18px;
    border-radius:14px;
    box-shadow:0 6px 20px rgba(0,0,0,0.08);
    margin-bottom:18px;
}

.filter-box form{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:12px;
}

select, button{
    padding:11px 12px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:14px;
}

button{
    background:#1e3c72;
    color:#fff;
    border:none;
    font-weight:800;
    cursor:pointer;
}
button:hover{background:#16305d;}

.stats{
    margin-top:16px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:14px;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:18px;
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

.table-box{
    margin-top:18px;
    background:#fff;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    overflow:hidden;
}

table{width:100%;border-collapse:collapse;}
thead{background:#1e3c72;color:#fff;}
th, td{padding:14px 16px;text-align:left;font-size:14px;}
tbody tr:nth-child(even){background:#f9fafc;}
tbody tr:hover{background:#eef2f7;}

.status{
    font-weight:800;
    padding:4px 10px;
    border-radius:999px;
    display:inline-block;
    font-size:12px;
}
.hadir{background:#eafff1;color:#1d8f4e;border:1px solid #bff5d2;}
.izin{background:#fff6e5;color:#b97700;border:1px solid #ffe2a8;}
.sakit{background:#e9f2ff;color:#1e3c72;border:1px solid #b8d4ff;}
.alpha{background:#ffeaea;color:#c0392b;border:1px solid #ffbcbc;}

@keyframes fadeIn{
    from{opacity:0;transform:translateY(10px);}
    to{opacity:1;transform:translateY(0);}
}
@media(max-width:768px){
    .main{padding:25px 18px;}
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">🎓 Sistem Akademik RFID</div>
    <div class="menu">
        <a href="dashboard_muhammadAzam.php">Beranda</a>
        <a href="biodata_muhammadAzam.php">Biodata</a>
        <a href="nilai_muhammadAzam.php">Nilai</a>
        <a href="kehadiran_muhammadAzam.php" class="active">Kehadiran</a>
        <a href="../login_muhammadAzam/logout_muhammadAzam.php" class="logout">Logout</a>
    </div>
</div>

<div class="main">
    <h2>🕒 Kehadiran Murid</h2>
    <div class="sub">
        Siswa: <b><?= htmlspecialchars($nama) ?></b> • ID: <b><?= htmlspecialchars($id_murid) ?></b>
    </div>

    <div class="filter-box">
        <form method="GET">

            <select name="kelas">
            <option value="">-- Semua Kelas --</option>
            <?php while($k = mysqli_fetch_assoc($qKelas)) {
                $kl = $k['kelas_muhammadAzam']; // hasilnya X / XI / XII
                $sel = ($kelas == $kl) ? "selected" : "";
            ?>
                <option value="<?= htmlspecialchars($kl) ?>" <?= $sel ?>>
                    <?= htmlspecialchars($kl) ?>
                </option>
            <?php } ?>
        </select>


            <select name="semester">
                <option value="">-- Semua Semester --</option>
                <option value="Ganjil" <?= ($semester=="Ganjil")?"selected":"" ?>>Ganjil</option>
                <option value="Genap" <?= ($semester=="Genap")?"selected":"" ?>>Genap</option>
            </select>

            <select name="tahun">
                <option value="">-- Semua Tahun Ajaran --</option>
                <?php while($t = mysqli_fetch_assoc($qTahun)) {
                    $th = $t['tahun_ajaran_muhammadAzam'];
                    $sel = ($tahun == $th) ? "selected" : "";
                ?>
                    <option value="<?= htmlspecialchars($th) ?>" <?= $sel ?>>
                        <?= htmlspecialchars($th) ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit">Terapkan Filter</button>
        </form>
    </div>

    <div class="stats">
        <div class="card">
            <h3><?= $total>0 ? $pHadir."%" : "-" ?></h3>
            <p>Hadir</p>
            <div class="small"><?= $total>0 ? "Jumlah: $hadir dari $total" : "Belum ada data" ?></div>
        </div>

        <div class="card">
            <h3><?= $total>0 ? $pIzin."%" : "-" ?></h3>
            <p>Izin</p>
            <div class="small"><?= $total>0 ? "Jumlah: $izin" : "Belum ada data" ?></div>
        </div>

        <div class="card">
            <h3><?= $total>0 ? $pSakit."%" : "-" ?></h3>
            <p>Sakit</p>
            <div class="small"><?= $total>0 ? "Jumlah: $sakit" : "Belum ada data" ?></div>
        </div>

        <div class="card">
            <h3><?= $total>0 ? $pAlpha."%" : "-" ?></h3>
            <p>Alpha</p>
            <div class="small"><?= $total>0 ? "Jumlah: $alpha" : "Belum ada data" ?></div>
        </div>
    </div>

    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Kelas</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($qList) == 0){ ?>
                    <tr>
                        <td colspan="5" style="color:#777;">Belum ada data kehadiran untuk filter ini.</td>
                    </tr>
                <?php } ?>

                <?php while($d = mysqli_fetch_assoc($qList)) { ?>
                    <tr>
                        <td><?= htmlspecialchars(date("d M Y", strtotime($d['tanggal_muhammadAzam']))) ?></td>
                        <td>
                            <span class="status <?= statusClass($d['status_muhammadAzam']) ?>">
                                <?= htmlspecialchars($d['status_muhammadAzam']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($d['kelas_muhammadAzam'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($d['semester_muhammadAzam'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($d['tahun_ajaran_muhammadAzam'] ?? '-') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
