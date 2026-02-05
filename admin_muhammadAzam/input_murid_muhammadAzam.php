<?php
include "../config_muhammadAzam/session_muhammadAzam.php";
include "../config_muhammadAzam/koneksi_muhammadAzam.php";

// =====================
// CEK LOGIN ADMIN
// =====================
if (!isset($_SESSION['role_muhammadAzam']) || $_SESSION['role_muhammadAzam'] != "admin") {
    header("Location: ../login_muhammadAzam/login_rfid_muhammadAzam.php");
    exit;
}

// =====================
// FUNCTION: GENERATE ID MRD-001
// =====================
function generateIdMurid($koneksi_muhammadAzam){
    $q = mysqli_query($koneksi_muhammadAzam, "
        SELECT id_murid_muhammadAzam 
        FROM tb_murid_muhammadAzam 
        ORDER BY id_murid_muhammadAzam DESC 
        LIMIT 1
    ");

    if(mysqli_num_rows($q) > 0){
        $d = mysqli_fetch_assoc($q);
        $lastId = $d['id_murid_muhammadAzam']; // contoh MRD-005
        $num = (int) substr($lastId, 4);
        $num++;
        return "MRD-" . str_pad($num, 3, "0", STR_PAD_LEFT);
    }else{
        return "MRD-001";
    }
}

$id_murid_otomatis = generateIdMurid($koneksi_muhammadAzam);

// =====================
// SIMPAN DATA
// =====================
$pesan = "";

if(isset($_POST['submit'])){

    // ambil data input
    $id_murid = generateIdMurid($koneksi_muhammadAzam);

    $rfid   = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['rfid_muhammadAzam']);
    $nama   = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['nama_lengkap_muhammadAzam']);
    $nis    = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['nis_muhammadAzam']);
    $nisn   = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['nisn_muhammadAzam']);

    $tempat = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['tempat_lahir_muhammadAzam']);
    $tgl    = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['tanggal_lahir_muhammadAzam']);

    $jk     = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['jenis_kelamin_muhammadAzam']);
    $agama  = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['agama_muhammadAzam']);
    $alamat = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['alamat_muhammadAzam']);

    $ayah   = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['nama_ayah_muhammadAzam']);
    $ibu    = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['nama_ibu_muhammadAzam']);
    $pekAyah= mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['pekerjaan_ayah_muhammadAzam']);
    $pekIbu = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['pekerjaan_ibu_muhammadAzam']);
    $hp     = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['no_hp_ortu_muhammadAzam']);

    $kelas  = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['kelas_muhammadAzam']);
    $tahun  = mysqli_real_escape_string($koneksi_muhammadAzam, $_POST['tahun_ajaran_muhammadAzam']);

    // =====================
    // VALIDASI RFID UNIK
    // =====================
    $cek = mysqli_query($koneksi_muhammadAzam, "
        SELECT rfid_muhammadAzam 
        FROM tb_murid_muhammadAzam 
        WHERE rfid_muhammadAzam = '$rfid'
        LIMIT 1
    ");

    if(mysqli_num_rows($cek) > 0){
        $pesan = "⚠️ RFID sudah dipakai murid lain!";
    }else{

        $sql = "INSERT INTO tb_murid_muhammadAzam (
            id_murid_muhammadAzam,
            rfid_muhammadAzam,
            nama_lengkap_muhammadAzam,
            nis_muhammadAzam,
            nisn_muhammadAzam,
            tempat_lahir_muhammadAzam,
            tanggal_lahir_muhammadAzam,
            jenis_kelamin_muhammadAzam,
            agama_muhammadAzam,
            alamat_muhammadAzam,
            nama_ayah_muhammadAzam,
            nama_ibu_muhammadAzam,
            pekerjaan_ayah_muhammadAzam,
            pekerjaan_ibu_muhammadAzam,
            no_hp_ortu_muhammadAzam,
            kelas_muhammadAzam,
            tahun_ajaran_muhammadAzam
        ) VALUES (
            '$id_murid',
            '$rfid',
            '$nama',
            '$nis',
            '$nisn',
            '$tempat',
            '$tgl',
            '$jk',
            '$agama',
            '$alamat',
            '$ayah',
            '$ibu',
            '$pekAyah',
            '$pekIbu',
            '$hp',
            '$kelas',
            '$tahun'
        )";

        $run = mysqli_query($koneksi_muhammadAzam, $sql);

        if($run){
            header("Location: data_murid_muhammadAzam.php?msg=sukses");
            exit;
        }else{
            $pesan = "❌ Gagal simpan data: " . mysqli_error($koneksi_muhammadAzam);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Input Murid</title>

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
.navbar .logo{font-size:18px;font-weight:800;}
.navbar .menu{display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.navbar .menu a{
    color:#fff;text-decoration:none;font-size:14px;
    padding:8px 12px;border-radius:12px;transition:0.25s;
}
.navbar .menu a:hover{background:rgba(255,255,255,0.16);}
.navbar .menu a.active{
    background:#fff;color:#1e3c72;font-weight:800;
    box-shadow:0 10px 20px rgba(0,0,0,0.15);
}
.navbar .menu a.logout{
    padding:8px 16px;border:1px solid #f1c40f;border-radius:999px;
    background:rgba(255,255,255,0.12);color:#f1c40f;font-weight:800;
}
.navbar .menu a.logout:hover{background:#f1c40f;color:#1e3c72;}

.main{padding:28px 40px;}

.header{
    background:#fff;
    border-radius:18px;
    padding:22px;
    box-shadow:0 12px 30px rgba(0,0,0,0.08);
    margin-bottom:18px;
}
.header h2{color:#1e3c72;font-size:22px;margin-bottom:6px;}
.header p{color:#666;font-size:13px;line-height:1.6;}
.badge{
    display:inline-block;margin-top:10px;
    padding:7px 14px;border-radius:999px;
    font-size:12px;font-weight:800;
    background:#eaf1ff;color:#1e3c72;
    border:1px solid rgba(30,60,114,0.18);
}

.card{
    background:#fff;
    border-radius:18px;
    padding:22px;
    box-shadow:0 12px 30px rgba(0,0,0,0.08);
}

.grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px 18px;
}

label{font-size:13px;color:#666;font-weight:700;}
input, select, textarea{
    width:100%;
    padding:11px 12px;
    border-radius:14px;
    border:1px solid #e6e6e6;
    outline:none;
    margin-top:6px;
    font-size:14px;
    transition:0.2s;
    background:#fff;
}
textarea{min-height:85px;resize:vertical;}
input:focus, select:focus, textarea:focus{
    border-color:#1e3c72;
    box-shadow:0 0 0 3px rgba(30,60,114,0.10);
}

.full{grid-column:1 / -1;}

.actions{
    display:flex;
    gap:12px;
    margin-top:18px;
    flex-wrap:wrap;
}
button{
    padding:11px 18px;
    border:none;
    border-radius:14px;
    font-weight:800;
    cursor:pointer;
    transition:0.25s;
    font-size:14px;
}
.btn-save{background:#1e3c72;color:#fff;}
.btn-save:hover{transform:translateY(-2px);box-shadow:0 12px 24px rgba(0,0,0,0.12);}
.btn-back{background:#eef2ff;color:#1e3c72;}
.btn-back:hover{background:#e2e9ff;}

.alert{
    margin-bottom:14px;
    padding:12px 14px;
    border-radius:14px;
    background:#fff6e5;
    border:1px solid #ffe2a8;
    color:#b97700;
    font-weight:700;
    font-size:13px;
}

@media(max-width:900px){
    .main{padding:20px 16px;}
    .grid{grid-template-columns:1fr;}
}
</style>
</head>

<body>

<div class="navbar">
    <div class="logo">🛠️ Admin RFID</div>
    <div class="menu">
        <a href="dashboard_admin_muhammadAzam.php">Dashboard</a>
        <a href="data_murid_muhammadAzam.php">Murid</a>
        <a href="../login_muhammadAzam/logout_muhammadAzam.php" class="logout">Logout</a>
    </div>
</div>

<div class="main">

    <div class="header">
        <h2>➕ Input Murid Baru</h2>
        <p>Isi biodata murid sesuai kolom tabel database.</p>
        <div class="badge">ID Otomatis: <?= htmlspecialchars($id_murid_otomatis) ?></div>
    </div>

    <div class="card">

        <?php if(!empty($pesan)) { ?>
            <div class="alert"><?= $pesan ?></div>
        <?php } ?>

        <form method="POST">

            <div class="grid">

                <div>
                    <label>RFID</label>
                    <input type="text" name="rfid_muhammadAzam" required placeholder="Contoh: RFIDMURID006 (isi dengan RFID)">
                </div>

                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap_muhammadAzam" required placeholder="Contoh: Ahmad Fadli">
                </div>

                <div>
                    <label>NIS</label>
                    <input type="text" name="nis_muhammadAzam" required placeholder="Contoh: 12345">
                </div>

                <div>
                    <label>NISN</label>
                    <input type="text" name="nisn_muhammadAzam" required placeholder="Contoh: 99887766">
                </div>

                <div>
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir_muhammadAzam" required placeholder="Contoh: Bandung">
                </div>

                <div>
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir_muhammadAzam" required>
                </div>

                <div>
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin_muhammadAzam" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label>Agama</label>
                    <input type="text" name="agama_muhammadAzam" required placeholder="Contoh: Islam">
                </div>

                <div class="full">
                    <label>Alamat</label>
                    <textarea name="alamat_muhammadAzam" required placeholder="Contoh: Jl. Melati No. 1"></textarea>
                </div>

                <div>
                    <label>Nama Ayah</label>
                    <input type="text" name="nama_ayah_muhammadAzam" required>
                </div>

                <div>
                    <label>Nama Ibu</label>
                    <input type="text" name="nama_ibu_muhammadAzam" required>
                </div>

                <div>
                    <label>Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah_muhammadAzam" required>
                </div>

                <div>
                    <label>Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu_muhammadAzam" required>
                </div>

                <div>
                    <label>No HP Orang Tua</label>
                    <input type="text" name="no_hp_ortu_muhammadAzam" required placeholder="Contoh: 081234567890">
                </div>

                <div>
                    <label>Kelas</label>
                    <select name="kelas_muhammadAzam" required>
                        <option value="">-- Pilih --</option>
                        <option value="X-A">X-A</option>
                        <option value="X-B">X-B</option>
                        <option value="X-C">X-C</option>
                        <option value="X-A">XI-A</option>
                        <option value="X-B">XI-B</option>
                        <option value="X-C">XI-C</option>
                        <option value="XI-A">XII-A</option>
                        <option value="XI-B">XII-B</option>
                        <option value="XII-A">XII-C</option>
                    </select>
                </div>

                <div class="full">
                    <label>Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran_muhammadAzam" required placeholder="Contoh: 2024/2025">
                </div>

            </div>

            <div class="actions">
                <button type="submit" name="submit" class="btn-save">💾 Simpan</button>
                <button type="button" class="btn-back" onclick="location.href='data_murid_muhammadAzam.php'">⬅ Kembali</button>
            </div>

        </form>
    </div>

</div>

</body>
</html>
