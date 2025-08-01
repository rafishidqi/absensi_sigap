<?php
if ($mod == '') {
    header('location:../404');
    echo 'kosong';
} else {
    include_once 'sw-mod/sw-header.php';
    if (!isset($_COOKIE['COOKIES_MEMBER']) && !isset($_COOKIE['COOKIES_COOKIES'])) {
        setcookie('COOKIES_MEMBER', '', 0, '/');
        setcookie('COOKIES_COOKIES', '', 0, '/');
        session_destroy();
        header("location:./");
    } else {

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
        ORDER BY p.tanggal
        ";

        $result = mysqli_query($connection, $query);
        if (!$result) {
            die('Query error: ' . mysqli_error($connection));
        }

        echo '
        <!-- App Capsule -->
        <div id="appCapsule">
            <div class="section mt-2">
                <div class="section-title">Data Patroli</div>
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
                ? '<img src="' . $base_url . 'uploads/' . $row['dokumentasi'] . '" style="max-height:60px;">'
                : '-';

            echo '
                <tr>
                    <td>' . $row['id_patroli'] . '</td>
                    <td>' . htmlspecialchars($row['nama_karyawan']) . '</td>
                    <td>' . htmlspecialchars($row['nama_lokasi']) . '</td>
                    <td>' . htmlspecialchars($row['nama_pekerjaan']) . '</td>
                    <td>' . $row['tanggal'] . '</td>
                    <td>' . $row['status'] . '</td>
                    <td>' . $row['rating'] . '</td>
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
    }

    include_once 'sw-mod/sw-footer.php';
}
?>
