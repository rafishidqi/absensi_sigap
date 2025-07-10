<?php
include_once '../sw-library/sw-config.php'; // untuk koneksi database

// Cek apakah file diunggah
if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
    $logo = $_FILES['logo'];
    $ext  = pathinfo($logo['name'], PATHINFO_EXTENSION);
    $allowed = ['png', 'jpg', 'jpeg', 'gif'];

    // Validasi tipe file
    if (!in_array(strtolower($ext), $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Format file tidak didukung']);
        exit;
    }

    // Ambil data site lama
    $getSite = $connection->query("SELECT site_logo FROM sw_site LIMIT 1");
    $rowSite = $getSite->fetch_assoc();

    $oldLogo = $rowSite['site_logo'];

    // Hapus logo lama jika ada
    if ($oldLogo && file_exists('../sw-content/' . $oldLogo)) {
        unlink('../sw-content/' . $oldLogo);
    }

    // Buat nama file baru
    $newLogoName = 'logo_' . time() . '.' . $ext;
    $targetPath = '../sw-content/' . $newLogoName;

    // Pindahkan file yang diupload
    if (move_uploaded_file($logo['tmp_name'], $targetPath)) {
        // Update database
        $update = $connection->query("UPDATE sw_site SET site_logo = '$newLogoName'");

        if ($update) {
            echo json_encode(['status' => 'success', 'message' => 'Logo berhasil diperbarui']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Tidak ada file yang dikirim']);
}
?>
