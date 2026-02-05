<?php
function generate_id($table, $prefix, $column, $koneksi) {
    $query = "SELECT $column FROM $table ORDER BY $column DESC LIMIT 1";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $last_id = $row[$column];
        $num = (int) substr($last_id, strlen($prefix));
        $num++;
        $new_id = $prefix . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $new_id = $prefix . '001';
    }

    return $new_id;
}

// Fungsi hashing password admin
function hash_password($password){
    return password_hash($password, PASSWORD_DEFAULT);
}
?>
