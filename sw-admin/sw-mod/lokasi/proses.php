<?php
session_start();
require_once '../../../sw-library/sw-config.php';


if (@$_GET['action'] == 'simpan') {
    $nama      = mysqli_real_escape_string($connection, $_POST['nama_lokasi']);
    $deskripsi = mysqli_real_escape_string($connection, $_POST['deskripsi_lokasi']);
    $koordinat = mysqli_real_escape_string($connection, $_POST['koordinat_gps']);

    $sql = "INSERT INTO tbl_lokasi (nama_lokasi, deskripsi_lokasi, koordinat_gps)
            VALUES ('$nama', '$deskripsi', '$koordinat')";

    if (mysqli_query($connection, $sql)) {
        echo '<script>alert("Data berhasil ditambahkan."); window.location.href="lokasi.php";</script>';
    } else {
        echo '<script>alert("Gagal menyimpan data."); window.history.back();</script>';
    }
}


if (@$_GET['action'] == 'update') {
    $id_lokasi = intval($_POST['id_lokasi']);
    $nama      = mysqli_real_escape_string($connection, $_POST['nama_lokasi']);
    $deskripsi = mysqli_real_escape_string($connection, $_POST['deskripsi_lokasi']);
    $koordinat = mysqli_real_escape_string($connection, $_POST['koordinat_gps']);

    $sql = "UPDATE tbl_lokasi 
            SET nama_lokasi = '$nama', 
                deskripsi_lokasi = '$deskripsi', 
                koordinat_gps = '$koordinat' 
            WHERE id_lokasi = '$id_lokasi'";

    if (mysqli_query($connection, $sql)) {
        echo '<script>alert("Data berhasil diperbarui."); window.location.href="lokasi.php";</script>';
    } else {
        echo '<script>alert("Gagal memperbarui data."); window.history.back();</script>';
    }
}


if (@$_GET['action'] == 'delete') {
    $id = intval($_POST['id']);
    $query = "DELETE FROM tbl_lokasi WHERE id_lokasi='$id'";
    echo (mysqli_query($connection, $query)) ? 'success' : 'Gagal menghapus data.';
}
