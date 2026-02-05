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
    SELECT id_murid_muhammadAzam, nama_lengkap_muhammadAzam, kelas_muhammadAzam, tahun_ajaran_muhammadAzam
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
$kelasDB  = $murid['kelas_muhammadAzam'] ?? "";
$tahunDB  = $murid['tahun_ajaran_muhammadAzam'] ?? "";

// =====================
// FILTER GET
// =====================
$kelas    = $_GET['kelas'] ?? '';
$semester = $_GET['semester'] ?? '';
$tahun    = $_GET['tahun'] ?? '';

if ($kelas == "") $kelas = $kelasDB;
if ($tahun == "") $tahun = $tahunDB;

// =====================
// LIST KELAS (DARI NILAI)
// =====================
$qKelas = mysqli_query($koneksi_muhammadAzam, "
    SELECT DISTINCT kelas_muhammadAzam
    FROM tb_nilai_muhammadAzam
    WHERE kelas_muhammadAzam IS NOT NULL AND kelas_muhammadAzam != ''
    ORDER BY kelas_muhammadAzam ASC
");

// =====================
// LIST TAHUN AJARAN (DARI NILAI)
// =====================
$qTahun = mysqli_query($koneksi_muhammadAzam, "
    SELECT DISTINCT tahun_ajaran_muhammadAzam
    FROM tb_nilai_muhammadAzam
    WHERE tahun_ajaran_muhammadAzam IS NOT NULL AND tahun_ajaran_muhammadAzam != ''
    ORDER BY tahun_ajaran_muhammadAzam DESC
");

// =====================
// QUERY LIST NILAI
// =====================
$sqlList = "
    SELECT 
        n.id_nilai_muhammadAzam,
        n.kelas_muhammadAzam,
        n.semester_muhammadAzam,
        n.tahun_ajaran_muhammadAzam,
        n.nilai_angka_muhammadAzam,
        n.nilai_huruf_muhammadAzam,
        m.nama_mapel_muhammadAzam
    FROM tb_nilai_muhammadAzam n
    LEFT JOIN tb_mapel_muhammadAzam m ON n.id_mapel_muhammadAzam = m.id_mapel_muhammadAzam
    WHERE n.id_murid_muhammadAzam = '$id_murid'
";

if ($kelas != "")    $sqlList .= " AND n.kelas_muhammadAzam = '$kelas' ";
if ($semester != "") $sqlList .= " AND n.semester_muhammadAzam = '$semester' ";
if ($tahun != "")    $sqlList .= " AND n.tahun_ajaran_muhammadAzam = '$tahun' ";

$sqlList .= " ORDER BY n.tahun_ajaran_muhammadAzam DESC, n.semester_muhammadAzam ASC, m.nama_mapel_muhammadAzam ASC";

$qList = mysqli_query($koneksi_muhammadAzam, $sqlList);

// =====================
// QUERY RATA-RATA
// =====================
$sqlAvg = "
    SELECT 
        AVG(nilai_angka_muhammadAzam) AS rata2,
        COUNT(*) AS total
    FROM tb_nilai_muhammadAzam
    WHERE id_murid_muhammadAzam = '$id_murid'
";

if ($kelas != "")    $sqlAvg .= " AND kelas_muhammadAzam = '$kelas' ";
if ($semester != "") $sqlAvg .= " AND semester_muhammadAzam = '$semester' ";
if ($tahun != "")    $sqlAvg .= " AND tahun_ajaran_muhammadAzam = '$tahun' ";

$qAvg = mysqli_query($koneksi_muhammadAzam, $sqlAvg);
$avg = mysqli_fetch_assoc($qAvg);

$rata2 = (!empty($avg['rata2'])) ? round($avg['rata2'], 1) : 0;
$total = (int)($avg['total'] ?? 0);

// =====================
// MENU AKTIF
// =====================
$activePage = "nilai";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Nilai Murid</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', 'Segoe UI', sans-serif;
}
body {
    background: #f4f6fb;
    color: #333;
    font-size: 14px;
}

.navbar {
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    color: #fff;
    padding: 16px 34px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    position: sticky;
    top: 0;
    z-index: 99;
}

.navbar .logo {
    font-size: 18px;
    font-weight: 700;
}

.navbar .menu {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.navbar .menu a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    padding: 7px 12px;
    border-radius: 12px;
    transition: 0.25s;
}

.navbar .menu a:hover {
    background: rgba(255, 255, 255, 0.15);
}

.navbar .menu a.active {
    background: #fff;
    color: #1e3c72;
    font-weight: 800;
}

.navbar .menu a.logout {
    padding: 7px 16px;
    border: 1px solid #f1c40f;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.12);
    color: #f1c40f;
    font-weight: 700;
}

.navbar .menu a.logout:hover {
    background: #f1c40f;
    color: #1e3c72;
}

/* Main Content Styling */
.main {
    padding: 32px 40px;
    animation: fadeIn 0.6s ease;
}

h2 {
    color: #1e3c72;
    margin-bottom: 8px;
}

.sub {
    color: #666;
    margin-bottom: 18px;
    font-size: 14px;
}

/* Filter Box Styling */
.filter-box {
    background: #fff;
    padding: 18px;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 18px;
}

.filter-box form {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

select,
input {
    padding: 11px 12px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 14px;
}

button {
    background: #1e3c72;
    color: #fff;
    border: none;
    font-weight: 700;
    cursor: pointer;
}

button:hover {
    background: #16305d;
}

/* Card Styling */
.card {
    background: #fff;
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(0, 0, 0, 0.03);
    position: relative;
    overflow: hidden;
    margin-bottom: 16px;
}

.card h3 {
    font-size: 22px;
    color: #1e3c72;
    margin-bottom: 6px;
}

.card p {
    font-size: 13px;
    color: #666;
}

/* Table Box Styling */
.table-box {
    margin-top: 18px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #1e3c72;
    color: #fff;
}

th,
td {
    padding: 14px 16px;
    text-align: left;
    font-size: 14px;
}

tbody tr:nth-child(even) {
    background: #f9fafc;
}

tbody tr:hover {
    background: #eef2f7;
}

/* Badge Styling */
.badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
    background: #eaf1ff;
    color: #1e3c72;
    border: 1px solid rgba(30, 60, 114, 0.18);
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .main {
        padding: 25px 18px;
    }
}

</style>
</head>

<body>

<div class="navbar">
    <div class="logo">🎓 Sistem Akademik RFID</div>
    <div class="menu">
        <a href="dashboard_muhammadAzam.php" class="<?= ($activePage=="dashboard")?"active":"" ?>">Beranda</a>
        <a href="biodata_muhammadAzam.php" class="<?= ($activePage=="biodata")?"active":"" ?>">Biodata</a>
        <a href="nilai_muhammadAzam.php" class="<?= ($activePage=="nilai")?"active":"" ?>">Nilai</a>
        <a href="kehadiran_muhammadAzam.php" class="<?= ($activePage=="kehadiran")?"active":"" ?>">Kehadiran</a>
        <a href="../login_muhammadAzam/logout_muhammadAzam.php" class="logout">Logout</a>
    </div>
</div>

<div class="main">

    <h2>📊 Nilai Murid</h2>
    <div class="sub">
        Siswa: <b><?= htmlspecialchars($nama) ?></b> • ID: <b><?= htmlspecialchars($id_murid) ?></b>
    </div>

    <div class="filter-box">
        <form method="GET">

            <select name="kelas">
                <option value="">-- Pilih Kelas --</option>
                <?php while($k = mysqli_fetch_assoc($qKelas)) {
                    $kl = $k['kelas_muhammadAzam'];
                    $sel = ($kelas == $kl) ? "selected" : "";
                ?>
                    <option value="<?= htmlspecialchars($kl) ?>" <?= $sel ?>>
                        <?= htmlspecialchars($kl) ?>
                    </option>
                <?php } ?>
            </select>

            <select name="semester">
                <option value="">-- Pilih Semester --</option>
                <option value="Ganjil" <?= ($semester=="Ganjil")?"selected":"" ?>>Ganjil</option>
                <option value="Genap" <?= ($semester=="Genap")?"selected":"" ?>>Genap</option>
            </select>

            <select name="tahun">
                <option value="">-- Pilih Tahun Ajaran --</option>
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

    <div class="card">
        <h3><?= $total > 0 ? $rata2 : "-" ?></h3>
        <p>Rata-rata Nilai (berdasarkan filter)</p>
        <div style="margin-top:10px;">
            <span class="badge">Total data nilai: <?= $total ?></span>
            <?php if($kelas!=""){ ?><span class="badge">Kelas: <?= htmlspecialchars($kelas) ?></span><?php } ?>
            <?php if($semester!=""){ ?><span class="badge">Semester: <?= htmlspecialchars($semester) ?></span><?php } ?>
            <?php if($tahun!=""){ ?><span class="badge">Tahun: <?= htmlspecialchars($tahun) ?></span><?php } ?>
        </div>
    </div>

    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Nilai Angka</th>
                    <th>Nilai Huruf</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($qList) == 0){ ?>
                    <tr>
                        <td colspan="7" style="color:#777;">Belum ada data nilai untuk filter ini.</td>
                    </tr>
                <?php } ?>

                <?php $no=1; while($d = mysqli_fetch_assoc($qList)) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($d['nama_mapel_muhammadAzam'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($d['kelas_muhammadAzam'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($d['semester_muhammadAzam'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($d['tahun_ajaran_muhammadAzam'] ?? '-') ?></td>
                        <td><b><?= htmlspecialchars($d['nilai_angka_muhammadAzam'] ?? '-') ?></b></td>
                        <td><?= htmlspecialchars($d['nilai_huruf_muhammadAzam'] ?? '-') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
