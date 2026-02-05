<?php
// ==========================================
// GENERATE ID TERPUSAT (AMAN DUPLIKASI)
// ==========================================

include "../config_muhammadAzam/koneksi_muhammadAzam.php";

// Fungsi generate ID otomatis
function generateID($koneksi_muhammadAzam, $prefix, $tabel, $kolom) {

    // Ambil ID terakhir
    $query = mysqli_query(
        $koneksi_muhammadAzam,
        "SELECT $kolom FROM $tabel 
         ORDER BY $kolom DESC LIMIT 1"
    );

    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $lastNumber = (int) substr($data[$kolom], strlen($prefix));
        $newNumber  = $lastNumber + 1;
    } else {
        $newNumber = 1;
    }

    // Format ID (contoh: MRD-0001)
    return $prefix . str_pad($newNumber, 4, "0", STR_PAD_LEFT);
}
