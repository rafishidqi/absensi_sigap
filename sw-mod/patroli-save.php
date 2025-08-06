<?php
include_once '../sw-library/sw-config.php'; // ganti sesuai file koneksi kamu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_karyawan = $_POST['id_karyawan'];
    $id_lokasi = $_POST['id_lokasi'];
    $id_ceklis = $_POST['id_ceklis'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];
    $rating = !empty($_POST['rating']) ? $_POST['rating'] : null;
    $komentar = !empty($_POST['komentar']) ? $_POST['komentar'] : null;

    $dokumentasi = null;
    if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === 0) {
        $uploadDir = 'uploads/patroli/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $filename = date('YmdHis') . '_' . basename($_FILES['dokumentasi']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['dokumentasi']['tmp_name'], $targetPath)) {
            $dokumentasi = $filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO tbl_patroli (id_karyawan, id_lokasi, id_ceklis, tanggal, status, rating, dokumentasi, komentar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiississ", $id_karyawan, $id_lokasi, $id_ceklis, $tanggal, $status, $rating, $dokumentasi, $komentar);

    if ($stmt->execute()) {
        header("Location: index.php?mod=patroli&success=1");
    } else {
        echo "Gagal menyimpan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
