proses
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

    // Removed the 'add' case entirely as per your request.
    // case 'add':
    //    // ... (removed code for adding new patrol entries)
    //    break;

    // ========== UPDATE KOMENTAR DAN RATING PATROLI ==========
    case 'update_comment_rating':
        $error = [];

        // Validasi ID Patroli
        if (empty($_POST['id_patroli']) || !is_numeric($_POST['id_patroli'])) {
            $error[] = 'ID Patroli tidak valid';
        } else {
            $id_patroli = (int)$_POST['id_patroli'];
        }

        // Only allow updating rating and komentar
        $rating   = !empty($_POST['rating']) ? anti_injection($_POST['rating']) : $error[] = 'Rating harus dipilih';
        $komentar = !empty($_POST['komentar']) ? anti_injection($_POST['komentar']) : '';

        // Check if the user has permission to update comment and rating
        if (!($level_user == 1 || $level_user == 2)) {
            $error[] = 'Anda tidak memiliki akses untuk memperbarui komentar atau rating.';
        }

        if (empty($error)) {
            // Retrieve existing data to ensure other fields are not nullified
            $query_check = "SELECT * FROM tbl_patroli WHERE id_patroli = '$id_patroli'";
            $result_check = $connection->query($query_check);
            if ($result_check && $result_check->num_rows > 0) {
                $row_exist = $result_check->fetch_assoc();

                $update = "UPDATE tbl_patroli SET
                            rating  = '$rating',
                            komentar = '$komentar'
                           WHERE id_patroli = '$id_patroli'";

                if ($connection->query($update)) {
                    header('Location: ../../patroli');
                    exit;
                } else {
                    echo '<div class="alert alert-danger">Gagal memperbarui komentar dan rating patroli: ' . $connection->error . '</div>';
                    echo '<a href="../../patroli&op=edit&id=' . $id_patroli . '" class="btn btn-default">Kembali</a>';
                }
            } else {
                echo '<div class="alert alert-danger">Data patroli tidak ditemukan.</div>';
                echo '<a href="../../patroli" class="btn btn-default">Kembali</a>';
            }
        } else {
            echo '<div class="alert alert-danger">';
            echo '<h4>Error Validasi:</h4><ul>';
            foreach ($error as $val) {
                echo '<li>' . $val . '</li>';
            }
            echo '</ul></div>';
            echo '<a href="../../patroli&op=edit&id=' . $_POST['id_patroli'] . '" class="btn btn-default">Kembali ke Form</a>';
        }
        break;

    // ========== DELETE PATROLI ==========
    case 'delete':
        if (!($level_user == 1)) {
            echo '<div class="alert alert-danger">Anda tidak memiliki akses untuk menghapus data patroli.</div>';
            echo '<a href="../../patroli" class="btn btn-default">Kembali</a>';
            exit;
        }

        if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
            echo '<div class="alert alert-danger">ID Patroli tidak valid.</div>';
            echo '<a href="../../patroli" class="btn btn-default">Kembali</a>';
            exit;
        }

        $id_patroli = (int)$_POST['id'];

        // Get documentation file path before deleting record
        $query_doc = "SELECT dokumentasi FROM tbl_patroli WHERE id_patroli = '$id_patroli'";
        $result_doc = $connection->query($query_doc);
        if ($result_doc && $result_doc->num_rows > 0) {
            $row_doc = $result_doc->fetch_assoc();
            $dokumentasi_file = $row_doc['dokumentasi'];

            // Delete the file if it exists
            if (!empty($dokumentasi_file) && file_exists("../../../sw-content/patroli/" . $dokumentasi_file)) {
                unlink("../../../sw-content/patroli/" . $dokumentasi_file);
            }
        }

        $delete = "DELETE FROM tbl_patroli WHERE id_patroli = '$id_patroli'";
        if ($connection->query($delete)) {
            header('Location: ../../patroli');
            exit;
        } else {
            echo '<div class="alert alert-danger">Gagal menghapus data patroli: ' . $connection->error . '</div>';
            echo '<a href="../../patroli" class="btn btn-default">Kembali</a>';
        }
        break;
}
?>