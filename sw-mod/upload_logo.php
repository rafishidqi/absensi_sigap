<?php
include '../sw-library/sw-config.php';

$response = ['status' => 'error', 'message' => 'Gagal'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme_color = $_POST['theme_color'];
    $logo_name = '';

    // Ambil logo lama dari database
    $cek = $connection->query("SELECT site_logo FROM sw_site LIMIT 1");
    $lama = $cek->fetch_assoc();
    $logo_lama = $lama['site_logo'];

    // Cek apakah ada file logo baru yang diupload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $new_name = 'logo_' . time() . '.' . $ext;
        $path = '../sw-content/' . $new_name;

        // Upload file ke server
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $path)) {
            // Jika upload berhasil, hapus logo lama jika ada
            if (!empty($logo_lama) && file_exists("../sw-content/" . $logo_lama)) {
                unlink("../sw-content/" . $logo_lama);
            }
            $logo_name = $new_name;
        } else {
            $response['message'] = 'Gagal upload gambar';
            echo json_encode($response);
            exit;
        }
    } else {
        // Tidak ada file baru, tetap gunakan logo lama
        $logo_name = $logo_lama;
    }

    // Simpan ke database
    $stmt = $connection->prepare("UPDATE sw_site SET site_logo=?, theme_color=? WHERE site_id=1");
    $stmt->bind_param("ss", $logo_name, $theme_color);

    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = 'Pengaturan berhasil disimpan';
    } else {
        $response['message'] = 'Gagal menyimpan ke database';
    }
}

echo json_encode($response);
