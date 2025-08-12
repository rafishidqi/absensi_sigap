<?php
session_start();
if (empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])) {
    header('Location: ../../login/');
    exit;
}

require_once '../../../sw-library/sw-config.php';
require_once '../../login/login_session.php';
include('../../../sw-library/sw-function.php');

switch (@$_GET['action']) {

    // ========== TAMBAH CHECKLIST ==========
    case 'add':
        $error = [];

        $nama_pekerjaan      = !empty($_POST['nama_pekerjaan']) ? anti_injection($_POST['nama_pekerjaan']) : $error[] = 'Nama Pekerjaan tidak boleh kosong';
        $deskripsi_pekerjaan = !empty($_POST['deskripsi_pekerjaan']) ? anti_injection($_POST['deskripsi_pekerjaan']) : $error[] = 'Deskripsi tidak boleh kosong';
        $kategori_pekerjaan  = !empty($_POST['kategori_pekerjaan']) ? anti_injection($_POST['kategori_pekerjaan']) : $error[] = 'Kategori tidak boleh kosong';

        if (empty($error)) {
            $add = "INSERT INTO tbl_checklist (
                        nama_pekerjaan, 
                        deskripsi_pekerjaan, 
                        kategori_pekerjaan
                    ) VALUES (
                        '$nama_pekerjaan', 
                        '$deskripsi_pekerjaan', 
                        '$kategori_pekerjaan'
                    )";

            if ($connection->query($add)) {
                header('Location: ../../checklist');
                exit;
            } else {
                die('Gagal menambahkan checklist: ' . $connection->error);
            }
        } else {
            foreach ($error as $val) {
                echo $val . '<br>';
            }
        }
        break;

    // ========== UPDATE CHECKLIST ==========
    case 'update':
        $error = [];

        $id                  = !empty($_POST['id']) ? anti_injection($_POST['id']) : $error[] = 'ID tidak boleh kosong';
        $nama_pekerjaan      = !empty($_POST['nama_pekerjaan']) ? anti_injection($_POST['nama_pekerjaan']) : $error[] = 'Nama Pekerjaan tidak boleh kosong';
        $deskripsi_pekerjaan = !empty($_POST['deskripsi_pekerjaan']) ? anti_injection($_POST['deskripsi_pekerjaan']) : $error[] = 'Deskripsi tidak boleh kosong';
        $kategori_pekerjaan  = !empty($_POST['kategori_pekerjaan']) ? anti_injection($_POST['kategori_pekerjaan']) : $error[] = 'Kategori tidak boleh kosong';

        if (empty($error)) {
            $update = "UPDATE tbl_checklist SET 
                        nama_pekerjaan = '$nama_pekerjaan',
                        deskripsi_pekerjaan = '$deskripsi_pekerjaan',
                        kategori_pekerjaan = '$kategori_pekerjaan'
                      WHERE id_checklist = '$id'";

            if ($connection->query($update)) {
                header('Location: ../../checklist');
                exit;
            } else {
                die('Gagal mengupdate checklist: ' . $connection->error);
            }
        } else {
            foreach ($error as $val) {
                echo $val . '<br>';
            }
        }
        break;

    // ========== HAPUS CHECKLIST ==========
    case 'delete':
        if (!empty($_POST['id'])) {
            $id = anti_injection(epm_decode($_POST['id']));

            $deleted = "DELETE FROM tbl_checklist WHERE id_checklist = '$id'";
            if ($connection->query($deleted)) {
                header('Location: ../../checklist');
                exit;
            } else {
                echo 'Data tidak berhasil dihapus!<br>' . $connection->error;
            }
        } else {
            echo 'ID tidak boleh kosong';
        }
        break;

    // ========== DEFAULT ==========
    default:
        echo 'Aksi tidak dikenali.';
        break;
}