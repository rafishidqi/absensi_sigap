<?php
require_once 'koneksi.php'; // sambungkan ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama_lokasi']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi_lokasi']);
    $koordinat = mysqli_real_escape_string($conn, $_POST['koordinat_gps']);

    $query = "INSERT INTO tbl_lokasi (nama_lokasi, deskripsi_lokasi, koordinat_gps)
              VALUES ('$nama', '$deskripsi', '$koordinat')";

    if (mysqli_query($conn, $query)) {
        echo "Data lokasi berhasil disimpan.";
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
