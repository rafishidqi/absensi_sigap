<?php
// Pastikan variabel $mod diatur. Jika tidak, redirect ke halaman 404.
if ($mod == '') {
    header('location:../404');
    echo 'kosong';
} else {
    // Memuat header.
    include_once 'sw-mod/sw-header.php';

    // Mendapatkan koneksi dari header.
    global $connection;

    // --- LOGIKA PHP UNTUK MENAMBAHKAN DATA PATROLI BARU ---
    // Pastikan form disubmit dan file diupload.
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_patrol'])) {
        // Ambil data dari form dan bersihkan dari input yang berbahaya.
        $id_karyawan = mysqli_real_escape_string($connection, $_POST['id_karyawan']);
        $id_lokasi = mysqli_real_escape_string($connection, $_POST['id_lokasi']);
        $id_ceklis = mysqli_real_escape_string($connection, $_POST['id_ceklis']);
        $tanggal = mysqli_real_escape_string($connection, $_POST['tanggal']);
        $status = mysqli_real_escape_string($connection, $_POST['status']);

        // Tangani upload file dokumentasi.
        $dokumentasi = '';
        if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['dokumentasi']['name'];
            $file_tmp = $_FILES['dokumentasi']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_new_name = uniqid('patroli_') . '.' . $file_ext;
            $upload_dir = '../uploads/'; // Pastikan direktori ini ada dan bisa ditulisi.
            $upload_path = $upload_dir . $file_new_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $dokumentasi = $file_new_name;
            } else {
                // Tangani error upload.
                die('Gagal mengupload file.');
            }
        }

        // Buat kueri INSERT. Rating dan komentar tidak diisi karena boleh null.
        $insert_query = "INSERT INTO tbl_patroli (id_karyawan, id_lokasi, id_ceklis, tanggal, status, dokumentasi) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($connection, $insert_query);
        mysqli_stmt_bind_param($stmt, "iissss", $id_karyawan, $id_lokasi, $id_ceklis, $tanggal, $status, $dokumentasi);

        // Jalankan kueri.
        if (mysqli_stmt_execute($stmt)) {
            // Redirect setelah sukses untuk menghindari resubmit form.
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            die('Error saat menambahkan data: ' . mysqli_error($connection));
        }
    }


    // Periksa otentikasi.
    if (!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])) {
        setcookie('COOKIES_MEMBER', '', 0, '/');
        setcookie('COOKIES_COOKIES', '', 0, '/');
        session_destroy();
        header("location:./");
    } else {
        // --- AMBIL DATA UNTUK DROPDOWN ---
        // Fetch data for the 'Lokasi' dropdown.
        $lokasi_query = "SELECT id_lokasi, nama_lokasi FROM tbl_lokasi ORDER BY nama_lokasi";
        $lokasi_result = mysqli_query($connection, $lokasi_query);

        // Fetch data for the 'Pekerjaan' dropdown.
        $checklist_query = "SELECT id_checklist, nama_pekerjaan FROM tbl_checklist ORDER BY nama_pekerjaan";
        $checklist_result = mysqli_query($connection, $checklist_query);

        // Query untuk mendapatkan nama karyawan yang sedang login
        $current_user_id = mysqli_real_escape_string($connection, $_COOKIE['COOKIES_MEMBER']);
        $user_query = "SELECT employees_name FROM employees WHERE id = '$current_user_id'";
        $user_result = mysqli_query($connection, $user_query);
        $user_row = mysqli_fetch_assoc($user_result);
        $current_user_name = $user_row['employees_name'];

        // --- QUERY UTAMA UNTUK MENAMPILKAN DATA TABEL ---
        $query = "
        SELECT p.id_patroli, 
               e.employees_name AS nama_karyawan, 
               l.nama_lokasi, 
               c.nama_pekerjaan, 
               p.tanggal, 
               p.status, 
               p.rating, 
               p.dokumentasi, 
               p.komentar
        FROM tbl_patroli p
        LEFT JOIN employees e ON p.id_karyawan = e.id
        LEFT JOIN tbl_lokasi l ON p.id_lokasi = l.id_lokasi
        LEFT JOIN tbl_checklist c ON p.id_ceklis = c.id_checklist
        ORDER BY p.id_patroli DESC"; // Mengubah urutan untuk menampilkan yang terbaru di atas

        $result = mysqli_query($connection, $query);
        if (!$result) {
            die('Query error: ' . mysqli_error($connection));
        }

        echo '
        <!-- App Capsule -->
        <head>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        </head>
        <div id="appCapsule">
            <div class="section mt-2">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="section-title">Data Patroli</div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatroliModal">
                        <ion-icon name="add-outline"></ion-icon> Tambah
                    </button>
                </div>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Karyawan</th>
                                    <th>Lokasi</th>
                                    <th>Pekerjaan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Rating</th>
                                    <th>Dokumentasi</th>
                                    <th>Komentar</th>
                                </tr>
                            </thead>
                            <tbody>';

        while ($row = mysqli_fetch_assoc($result)) {
            $dokumentasi = !empty($row['dokumentasi'])
                ? '<img src="' . $base_url . 'uploads/' . htmlspecialchars($row['dokumentasi']) . '" style="max-height:60px;">'
                : '-';

            echo '
                <tr>
                    <td>' . htmlspecialchars($row['id_patroli']) . '</td>
                    <td>' . htmlspecialchars($row['nama_karyawan']) . '</td>
                    <td>' . htmlspecialchars($row['nama_lokasi']) . '</td>
                    <td>' . htmlspecialchars($row['nama_pekerjaan']) . '</td>
                    <td>' . htmlspecialchars($row['tanggal']) . '</td>
                    <td>' . htmlspecialchars($row['status']) . '</td>
                    <td>' . htmlspecialchars($row['rating']) . '</td>
                    <td>' . $dokumentasi . '</td>
                    <td>' . nl2br(htmlspecialchars($row['komentar'])) . '</td>
                </tr>';
        }

        echo '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>'; // Akhir dari appCapsule

        echo '
        <!-- Modal untuk Tambah Data Patroli -->
        <div class="modal fade" id="addPatroliModal" tabindex="-1" aria-labelledby="addPatroliModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPatroliModalLabel">Tambah Data Patroli</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="add_patrol" value="1">
                            
                            <div class="form-group mb-3">
                                <label for="id_karyawan" class="form-label">Karyawan</label>
                                <!-- ID Karyawan diambil dari session/cookie login dan disisipkan di sini -->
                                <input type="hidden" id="id_karyawan" name="id_karyawan" value="' . htmlspecialchars($current_user_id) . '" required>
                                <p class="form-control-plaintext">**' . htmlspecialchars($current_user_name) . '**</p>
                            </div>

                            <div class="form-group mb-3">
                                <label for="id_lokasi" class="form-label">Lokasi</label>
                                <select class="form-control" id="id_lokasi" name="id_lokasi" required>
                                    <option value="">Pilih Lokasi</option>';
        mysqli_data_seek($lokasi_result, 0);
        while ($lok_row = mysqli_fetch_assoc($lokasi_result)) {
            echo '<option value="' . htmlspecialchars($lok_row['id_lokasi']) . '">' . htmlspecialchars($lok_row['nama_lokasi']) . '</option>';
        }
        echo '</select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="id_ceklis" class="form-label">Pekerjaan (Ceklis)</label>
                                <select class="form-control" id="id_ceklis" name="id_ceklis" required>
                                    <option value="">Pilih Pekerjaan</option>';
        mysqli_data_seek($checklist_result, 0);
        while ($cek_row = mysqli_fetch_assoc($checklist_result)) {
            echo '<option value="' . htmlspecialchars($cek_row['id_checklist']) . '">' . htmlspecialchars($cek_row['nama_pekerjaan']) . '</option>';
        }
        echo '</select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Selesai">Selesai</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="dokumentasi" class="form-label">Dokumentasi</label>
                                <input type="file" class="form-control" id="dokumentasi" name="dokumentasi" accept="image/*">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        ';
    }

    include_once 'sw-mod/sw-footer.php';
}
