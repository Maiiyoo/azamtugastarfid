<?php
// Memulai session
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login RFID | Sistem Akademik</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg,#e3ebf6,#f6f8fb);
}

/* Container utama */
.container{
    width:900px;
    max-width:95%;
    background:#ffffff;
    display:flex;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 20px 45px rgba(0,0,0,0.12);
    animation: fadeIn 0.6s ease;
}

/* KIRI */
.left{
    flex:1;
    background: linear-gradient(135deg,#1e3c72,#2a5298);
    color:#ffffff;
    padding:50px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
}

.left h1{
    font-size:34px;
    margin-bottom:20px;
}

.left p{
    font-size:15px;
    line-height:1.6;
    opacity:0.9;
    margin-bottom:30px;
}

.left img{
    max-width:55%;
    transition:0.3s;
}

.left img:hover{
    transform:scale(1.03);
}

/* KANAN */
.right{
    flex:1;
    background:#f4f6fb;
    padding:50px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.right h2{
    font-size:26px;
    color:#1d1f2f;
    margin-bottom:8px;
}

.right p{
    font-size:14px;
    color:#555;
    margin-bottom:25px;
}

.right input{
    width:100%;
    padding:14px;
    font-size:15px;
    border-radius:12px;
    border:1px solid #ccc;
    outline:none;
    transition:0.3s;
}

.right input:focus{
    border-color:#1e3c72;
    box-shadow:0 0 6px rgba(30,60,114,0.25);
}

.error{
    margin-top:15px;
    color:#c0392b;
    font-size:14px;
}

/* Animasi */
@keyframes fadeIn{
    from{opacity:0; transform:translateY(15px);}
    to{opacity:1; transform:translateY(0);}
}

/* Responsive */
@media(max-width:768px){
    .container{
        flex-direction:column;
    }
}
</style>
</head>

<body>

<div class="container">

    <!-- KIRI -->
    <div class="left">
        <h1>Sistem Akademik RFID</h1>
        <p>
            Sistem informasi sekolah berbasis RFID  
            untuk login siswa dan admin secara cepat dan aman.
        </p>
        <img src="../assets_muhammadAzam/img/logo_sekolah.png" alt="Logo Sekolah">
    </div>

    <!-- KANAN -->
    <div class="right">
        <h2>Scan Kartu RFID</h2>
        <p>Tempelkan kartu RFID Anda pada reader</p>

        <form action="proses_login_muhammadAzam.php" method="POST" id="formRFID">
            <input type="text"
                   name="rfid_muhammadAzam"
                   id="rfid_muhammadAzam"
                   placeholder="Scan RFID"
                   autofocus
                   required>
        </form>

        <?php if (isset($_GET['error'])) { ?>
            <div class="error">RFID tidak terdaftar dalam sistem</div>
        <?php } ?>
    </div>

</div>

<script>
// Auto submit RFID
const rfid = document.getElementById("rfid_muhammadAzam");
rfid.addEventListener("input", function(){
    if(this.value.length >= 8){
        document.getElementById("formRFID").submit();
    }
});
</script>

</body>
</html>
