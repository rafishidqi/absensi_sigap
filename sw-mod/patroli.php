<?php
if ($mod == '') {
    header('location:../404');
    echo 'kosong';
} else {
    include_once 'sw-mod/sw-header.php';
    global $connection;

    // Validasi dan ambil data user login dari cookie
    if (!isset($_COOKIE['COOKIES_MEMBER']) || empty($_COOKIE['COOKIES_MEMBER'])) {
        setcookie('COOKIES_MEMBER', '', 0, '/');
        setcookie('COOKIES_COOKIES', '', 0, '/');
        session_destroy();
        header("location:./");
        exit;
    }

    $current_user_id = (int) $_COOKIE['COOKIES_MEMBER'];
    $user_query = mysqli_query($connection, "SELECT id, employees_name FROM employees WHERE id = '$current_user_id'");
    
    // Ambil semua data lokasi untuk dropdown dan logika peta
    $lokasi_data = [];
    $lokasi_result_for_dropdown = mysqli_query($connection, "SELECT id_lokasi, nama_lokasi FROM tbl_lokasi ORDER BY nama_lokasi");
    $lokasi_result_for_map = mysqli_query($connection, "SELECT id_lokasi, nama_lokasi, koordinat_gps, radius FROM tbl_lokasi ORDER BY nama_lokasi");
    while ($row_map = mysqli_fetch_assoc($lokasi_result_for_map)) {
        $koordinat = explode(',', $row_map['koordinat_gps']);
        $lokasi_data[] = [
            'id' => $row_map['id_lokasi'],
            'nama' => $row_map['nama_lokasi'],
            'lat' => (float) $koordinat[0],
            'lng' => (float) $koordinat[1],
            'radius' => (int) $row_map['radius']
        ];
    }
    
    $checklist_result = mysqli_query($connection, "SELECT id_checklist, nama_pekerjaan FROM tbl_checklist ORDER BY nama_pekerjaan");
    
    // Tambah data patroli
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_patrol'])) {
        $id_karyawan = (int) $_POST['id_karyawan'];
        $id_lokasi = (int) $_POST['id_lokasi'];
        $id_ceklis = (int) $_POST['id_ceklis'];
        $tanggal = mysqli_real_escape_string($connection, $_POST['tanggal']);
        $status = mysqli_real_escape_string($connection, $_POST['status']);
        $dokumentasi = '';
        $error_message = '';
    
        // Proses upload dokumentasi
        if (isset($_FILES['dokumentasi']) && $_FILES['dokumentasi']['error'] === UPLOAD_ERR_OK) {
            $file_name = $_FILES['dokumentasi']['name'];
            $file_tmp = $_FILES['dokumentasi']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_new_name = 'patroli_' . date('Ymd_His') . '_' . uniqid() . '.' . $file_ext;
            $upload_dir = 'sw-mod/uploads/';
            
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
    
            $upload_path = $upload_dir . $file_new_name;
    
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $dokumentasi = $file_new_name;
            } else {
                $error_message = 'GAGAL UPLOAD: Periksa izin (permission) pada folder "uploads".';
            }
        }
    
        // Lanjutkan hanya jika tidak ada error upload
        if (empty($error_message)) {
            $insert_query = "INSERT INTO tbl_patroli (id_karyawan, id_lokasi, id_ceklis, tanggal, status, dokumentasi) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $insert_query);
            mysqli_stmt_bind_param($stmt, "iiisss", $id_karyawan, $id_lokasi, $id_ceklis, $tanggal, $status, $dokumentasi);
    
            if (mysqli_stmt_execute($stmt)) {
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                $error_message = 'ERROR DATABASE: ' . mysqli_error($connection);
            }
        }
    
        // Jika ada error, tampilkan alert
        if (!empty($error_message)) {
            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($error_message) . '</div>';
        }
    }
    
    // Query tampil data
    $query = "
    SELECT p.id_patroli, e.employees_name AS nama_karyawan, l.nama_lokasi, 
            c.nama_pekerjaan, p.tanggal, p.status, p.rating, 
            p.dokumentasi, p.komentar
    FROM tbl_patroli p
    LEFT JOIN employees e ON p.id_karyawan = e.id
    LEFT JOIN tbl_lokasi l ON p.id_lokasi = l.id_lokasi
    LEFT JOIN tbl_checklist c ON p.id_ceklis = c.id_checklist
    ORDER BY p.id_patroli ";
    $result = mysqli_query($connection, $query);
    
    echo '
    <head>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
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
        $dokumentasi = !empty($row['dokumentasi']) ?
            '<img src="' . $base_url . 'sw-mod/uploads/' . htmlspecialchars($row['dokumentasi']) . '" style="max-height:60px;">' : '-';
    
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
    </div>';
    
    // Modal Form
    echo '
    <div class="modal fade" id="addPatroliModal" tabindex="-1" aria-labelledby="addPatroliModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="add_patrol" value="1">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPatroliModalLabel">Tambah Data Patroli</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_karyawan" value="' . htmlspecialchars($row_user['id']) . '" required>
                        <div class="form-group mb-3">
                            <label class="form-label">Karyawan</label>
                            <p class="form-control-plaintext"><b>' . ucfirst($row_user['employees_name']) . '</b></p>
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="id_lokasi" class="form-label">Lokasi</label>
                            <select class="form-control" name="id_lokasi" id="id_lokasi_patroli" required>
                                <option value="">Pilih Lokasi</option>';
    mysqli_data_seek($lokasi_result_for_dropdown, 0);
    while ($lok_row = mysqli_fetch_assoc($lokasi_result_for_dropdown)) {
        echo '<option value="' . htmlspecialchars($lok_row['id_lokasi']) . '">' . htmlspecialchars($lok_row['nama_lokasi']) . '</option>';
    }
    echo '
                            </select>
                        </div>
    
                        <div id="map-container" style="display: none; margin-bottom: 1rem;">
                            <label class="form-label">Peta Lokasi</label>
                            <div id="map-patroli" style="height:300px; border:1px solid #ccc;"></div>
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="id_ceklis" class="form-label">Pekerjaan (Ceklis)</label>
                            <select class="form-control" name="id_ceklis" required>
                                <option value="">Pilih Pekerjaan</option>';
    mysqli_data_seek($checklist_result, 0);
    while ($cek_row = mysqli_fetch_assoc($checklist_result)) {
        echo '<option value="' . htmlspecialchars($cek_row['id_checklist']) . '">' . htmlspecialchars($cek_row['nama_pekerjaan']) . '</option>';
    }
    echo '
                            </select>
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" required>
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Pending">Pending</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>
    
                        <div class="form-group mb-3">
                            <label for="dokumentasi" class="form-label">Dokumentasi</label>
                            <input type="file" class="form-control" name="dokumentasi" accept="image/*">
                        </div>
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>';
    
    include_once 'sw-mod/sw-footer.php';
}
?>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Embed data lokasi dari PHP
        const lokasiData = <?php echo json_encode($lokasi_data); ?>;
        const lokasiSelect = document.getElementById('id_lokasi_patroli');
        const mapContainer = document.getElementById('map-container');
        const mapElement = document.getElementById('map-patroli');
        let map = null;
        let marker = null;
        let circle = null;

        // Fungsi untuk menginisialisasi atau memperbarui peta
        function updateMap(lat, lng, radius) {
            // Hapus peta yang sudah ada jika ada
            if (map !== null) {
                map.remove();
            }

            // Tampilkan container peta
            mapContainer.style.display = 'block';

            // Inisialisasi peta baru
            map = L.map(mapElement).setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Tambahkan marker dan lingkaran
            marker = L.marker([lat, lng]).addTo(map);
            if (radius > 0) {
                circle = L.circle([lat, lng], { radius: radius, color: "blue", fillOpacity: 0.2 }).addTo(map);
                map.fitBounds(circle.getBounds());
            } else {
                map.setView([lat, lng], 16);
            }
        }

        // Event listener untuk dropdown lokasi
        lokasiSelect.addEventListener('change', function() {
            const selectedId = this.value;
            if (selectedId) {
                const selectedLokasi = lokasiData.find(lokasi => lokasi.id == selectedId);
                if (selectedLokasi) {
                    updateMap(selectedLokasi.lat, selectedLokasi.lng, selectedLokasi.radius);
                }
            } else {
                // Sembunyikan peta jika tidak ada lokasi yang dipilih
                mapContainer.style.display = 'none';
                if (map !== null) {
                    map.remove();
                    map = null;
                }
            }
        });
    });
</script>