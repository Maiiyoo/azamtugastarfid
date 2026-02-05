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
$filter_name = isset($_GET['filter_name']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['filter_name']) : '';
$filter_nip = isset($_GET['filter_nip']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['filter_nip']) : '';
$filter_mapel = isset($_GET['filter_mapel']) ? mysqli_real_escape_string($koneksi_muhammadAzam, $_GET['filter_mapel']) : '';

// Base query
$query = "SELECT g.*, m.nama_mapel_muhammadAzam
          FROM tb_guru_muhammadAzam g
          INNER JOIN tb_mapel_muhammadAzam m ON g.id_mapel_muhammadAzam = m.id_mapel_muhammadAzam
          WHERE 1";

// Add filters if provided
if ($filter_name) {
    $query .= " AND g.nama_lengkap_muhammadAzam LIKE '%$filter_name%'";
}

if ($filter_nip) {
    $query .= " AND g.nip_muhammadAzam LIKE '%$filter_nip%'";
}

if ($filter_mapel) {
    $query .= " AND m.nama_mapel_muhammadAzam LIKE '%$filter_mapel%'";
}

$result = mysqli_query($koneksi_muhammadAzam, $query);
$teachers = mysqli_fetch_all($result, MYSQLI_ASSOC);

function aman($val){
    return htmlspecialchars($val ?? "0", ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Guru</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
<style>
/* General Styling */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', 'Segoe UI', sans-serif; }
body { background: #f4f6fb; color: #333; font-size: 14px; line-height: 1.6; }

/* Sidebar */
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
.main { flex: 1; padding: 26px 28px; }
.topbar { display: flex; justify-content: space-between; align-items: flex-start; gap: 20px; margin-bottom: 18px; }
.topbar h2 { font-size: 24px; color: #1e3c72; font-weight: 900; }
.topbar p { font-size: 13px; color: #666; margin-top: 6px; line-height: 1.6; }

/* Filter Form */
form { margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 15px; }
form div { margin-bottom: 12px; flex: 1; }
form label { font-weight: 600; margin-bottom: 6px; display: inline-block; }
form input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
form button { background-color: #1e3c72; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; width: auto; }
form button:hover { background-color: #2a5298; }

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
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
            <a href="data_guru_muhammadAzam.php" class="active">👨‍🏫 Data Guru</a>
            <a href="data_mapel_muhammadAzam.php">📚 Data Mapel</a>
            <a href="data_siswa_muhammadAzam.php">👥 Data Siswa</a>
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
                <h2>Data Guru</h2>
                <p>Berikut adalah daftar guru yang terdaftar di sistem.</p>
            </div>
        </div>

        <!-- FILTER FORM -->
        <form method="get" action="data_guru_muhammadAzam.php">
            <div>
                <label for="filter_name">Nama Guru:</label>
                <input type="text" name="filter_name" id="filter_name" value="<?= aman($filter_name); ?>" placeholder="Cari nama guru">
            </div>
            <div>
                <label for="filter_nip">NIP:</label>
                <input type="text" name="filter_nip" id="filter_nip" value="<?= aman($filter_nip); ?>" placeholder="Cari NIP">
            </div>
            <div>
                <label for="filter_mapel">Mata Pelajaran:</label>
                <input type="text" name="filter_mapel" id="filter_mapel" value="<?= aman($filter_mapel); ?>" placeholder="Cari mata pelajaran">
            </div>
            <button type="submit">Filter</button>
        </form>

        <!-- TABLE -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>NIP</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Agama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Mapel</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teachers as $index => $teacher) : ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= aman($teacher['nama_lengkap_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['nip_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['tempat_lahir_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['tanggal_lahir_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['jenis_kelamin_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['agama_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['alamat_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['no_hp_guru_muhammadAzam']); ?></td>
                        <td><?= aman($teacher['nama_mapel_muhammadAzam']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
