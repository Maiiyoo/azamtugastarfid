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

// Handle Filter
$filter_kelas = isset($_GET['filter_kelas']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['filter_kelas']) : '';
$filter_semester = isset($_GET['filter_semester']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['filter_semester']) : '';
$filter_tahun_ajaran = isset($_GET['filter_tahun_ajaran']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['filter_tahun_ajaran']) : '';
$filter_nama = isset($_GET['filter_nama']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['filter_nama']) : '';
$orderby = isset($_GET['orderby']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['orderby']) : 'n.tahun_ajaran_muhammadAzam DESC, n.semester_muhammadAzam ASC, m.nama_mapel_muhammadAzam ASC';

// Base query
$query = "SELECT n.*, m.nama_mapel_muhammadAzam, s.nama_lengkap_muhammadAzam, s.kelas_muhammadAzam
          FROM tb_nilai_muhammadAzam n
          INNER JOIN tb_mapel_muhammadAzam m ON n.id_mapel_muhammadAzam = m.id_mapel_muhammadAzam
          INNER JOIN tb_murid_muhammadAzam s ON n.id_murid_muhammadAzam = s.id_murid_muhammadAzam
          WHERE 1";

// Add filters if provided
if ($filter_kelas) {
    $query .= " AND s.kelas_muhammadAzam = '$filter_kelas'";
}

if ($filter_semester) {
    $query .= " AND n.semester_muhammadAzam = '$filter_semester'";
}

if ($filter_tahun_ajaran) {
    $query .= " AND n.tahun_ajaran_muhammadAzam = '$filter_tahun_ajaran'";
}

if ($filter_nama) {
    $query .= " AND (s.nama_lengkap_muhammadAzam LIKE '%$filter_nama%' OR s.nis_muhammadAzam LIKE '%$filter_nama%')";
}

$query .= " ORDER BY $orderby";

$result = mysqli_query($koneksi_muhammadAzam, $query);
$nilai = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Fetch kelas and tahun ajaran for filter options
$kelas_query = "SELECT DISTINCT kelas_muhammadAzam FROM tb_murid_muhammadAzam";
$kelas_result = mysqli_query($koneksi_muhammadAzam, $kelas_query);
$kelas_options = mysqli_fetch_all($kelas_result, MYSQLI_ASSOC);

$tahun_ajaran_query = "SELECT DISTINCT tahun_ajaran_muhammadAzam FROM tb_nilai_muhammadAzam";
$tahun_ajaran_result = mysqli_query($koneksi_muhammadAzam, $tahun_ajaran_query);
$tahun_ajaran_options = mysqli_fetch_all($tahun_ajaran_result, MYSQLI_ASSOC);

function aman($val){
    return htmlspecialchars($val ?? "0", ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', 'Segoe UI', sans-serif; }
        body { background: #f4f6fb; color: #333; font-size: 14px; line-height: 1.6; }

        /* Sidebar */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e3c72, #2a5298);
            color: #fff;
            padding: 22px 18px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow: auto;
        }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 900; font-size: 16px; margin-bottom: 20px; }
        .brand .icon { width: 42px; height: 42px; border-radius: 14px; background: rgba(255, 255, 255, 0.16); display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .brand small { display: block; font-size: 12px; opacity: 0.85; font-weight: 600; margin-top: 2px; }
        .nav { display: flex; flex-direction: column; gap: 8px; margin-top: 10px; }
        .nav a { color: #fff; text-decoration: none; font-size: 14px; padding: 11px 12px; border-radius: 14px; transition: 0.25s; display: flex; align-items: center; gap: 10px; opacity: 0.95; font-weight: 600; }
        .nav a:hover { background: rgba(255, 255, 255, 0.14); transform: translateX(3px); }
        .nav a.active { background: #fff; color: #1e3c72; font-weight: 900; box-shadow: 0 12px 22px rgba(0, 0, 0, 0.15); }
        .sidebar .divider { margin: 16px 0; height: 1px; background: rgba(255, 255, 255, 0.18); }
        .logout a { background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(241, 196, 15, 0.7); color: #f1c40f; font-weight: 900; justify-content: center; }
        .logout a:hover { background: #f1c40f; color: #1e3c72; transform: none; }

        /* Main Content */
        .main {
            flex: 1;
            padding: 26px 28px;
            overflow: auto;
        }
        .topbar { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 18px; }
        .topbar h2 { font-size: 24px; color: #1e3c72; font-weight: 900; }
        .topbar p { font-size: 13px; color: #666; margin-top: 6px; line-height: 1.6; }

        /* Filter Form (Updated) */
        form { 
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        form div { 
            margin-bottom: 12px; 
            flex: 1;
            min-width: 160px;
        }
        form label { 
            font-weight: 600; 
            margin-bottom: 6px; 
            display: inline-block; 
        }
        form input, form select {
            width: 100%; 
            padding: 8px;
            border: 1px solid #ddd; 
            border-radius: 8px;
            font-size: 14px;
        }
        form button {
            background-color: #1e3c72; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 8px; 
            font-weight: 600; 
            cursor: pointer; 
            width: auto;
        }
        form button:hover { 
            background-color: #2a5298; 
        }

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            overflow-x: auto;
        }
        .table th, .table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 12px;
        }
        .table th {
            background-color: #1e3c72;
            color: white;
            font-weight: 600;
        }
        .table td {
            background-color: #fff;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }

        /* Responsive Styling */
        @media(max-width: 900px) {
            .sidebar { display: none; }
            .main { padding: 20px 18px; }
            .topbar { flex-direction: column; align-items: flex-start; }
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
            <a href="data_murid_muhammadAzam.php">👥 Data Siswa</a>
            <a href="data_nilai_muhammadAzam.php" class="active">📝 Data Nilai</a>
        </nav>

        <div class="divider"></div>

        <div class="nav logout">
            <a href="../login_muhammadAzam/logout_muhammadAzam.php">🚪 Logout</a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">
        <div class="topbar">
            <div>
                <h2>Data Nilai</h2>
                <p>Berikut adalah data nilai murid yang terdaftar di sistem.</p>
            </div>
        </div>

        <!-- FILTER FORM -->
        <form method="get" action="data_nilai_muhammadAzam.php">
            <div>
                <label for="filter_nama">Nama/NIS:</label>
                <input type="text" name="filter_nama" value="<?= htmlspecialchars($filter_nama) ?>" placeholder="Cari Nama atau NIS">
            </div>

            <div>
                <label for="filter_kelas">Kelas:</label>
                <select name="filter_kelas" id="filter_kelas">
                    <option value="">Semua Kelas</option>
                    <option value="X-A" <?= $filter_kelas == 'X-A' ? 'selected' : '' ?>>X-A</option>
                    <option value="X-B" <?= $filter_kelas == 'X-B' ? 'selected' : '' ?>>X-B</option>
                    <option value="XI-A" <?= $filter_kelas == 'XI-A' ? 'selected' : '' ?>>XI-A</option>
                    <option value="XI-B" <?= $filter_kelas == 'XI-B' ? 'selected' : '' ?>>XI-B</option>
                    <option value="XII-A" <?= $filter_kelas == 'XII-A' ? 'selected' : '' ?>>XII-A</option>
                    <option value="XII-B" <?= $filter_kelas == 'XII-B' ? 'selected' : '' ?>>XII-B</option>
                </select>
            </div>

            <div>
                <label for="filter_semester">Semester:</label>
                <select name="filter_semester" id="filter_semester">
                    <option value="">Semua Semester</option>
                    <option value="Ganjil" <?= $filter_semester == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
                    <option value="Genap" <?= $filter_semester == 'Genap' ? 'selected' : '' ?>>Genap</option>
                </select>
            </div>

            <div>
                <label for="filter_tahun_ajaran">Tahun Ajaran:</label>
                <select name="filter_tahun_ajaran" id="filter_tahun_ajaran">
                    <option value="">Semua Tahun Ajaran</option>
                    <option value="2024/2025" <?= $filter_tahun_ajaran == '2024/2025' ? 'selected' : '' ?>>2024/2025</option>
                    <option value="2025/2026" <?= $filter_tahun_ajaran == '2025/2026' ? 'selected' : '' ?>>2025/2026</option>
                </select>
            </div>

            <div>
                <button type="submit">Terapkan Filter</button>
            </div>
        </form>

        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Murid</th>
                    <th>Nama Mapel</th>
                    <th>Kelas</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Nilai Angka</th>
                    <th>Nilai Huruf</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) == 0) { ?>
                    <tr>
                        <td colspan="8" style="color:#777;">Belum ada data nilai untuk filter ini.</td>
                    </tr>
                <?php } ?>

                <?php $no = 1; foreach ($nilai as $n) { ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= aman($n['nama_lengkap_muhammadAzam']) ?></td>
                        <td><?= aman($n['nama_mapel_muhammadAzam']) ?></td>
                        <td><?= aman($n['kelas_muhammadAzam']) ?></td>
                        <td><?= aman($n['semester_muhammadAzam']) ?></td>
                        <td><?= aman($n['tahun_ajaran_muhammadAzam']) ?></td>
                        <td><?= aman($n['nilai_angka_muhammadAzam']) ?></td>
                        <td><?= aman($n['nilai_huruf_muhammadAzam']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</div>

</body>
</html>

