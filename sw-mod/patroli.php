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
        echo '
        <!-- App Capsule -->
        <div id="appCapsule">

            <!-- Section: Tambah Patroli Button -->
            <div class="section mt-2 text-center">
                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add-patroli">
                    <ion-icon name="add-outline"></ion-icon> Tambah Patroli
                </button>
            </div>

            <!-- Section: Data Patroli -->
            <div class="section mt-3">
                <h5 class="section-title mb-2">Data Patroli</h5>
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Lokasi</th>
                                    <th>Checklist</th>
                                    <th>Rating</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dummy data -->
                                <tr>
                                    <td>2025-07-31</td>
                                    <td>Pos Utama</td>
                                    <td>CCTV, Pintu, Lampu</td>
                                    <td><span class="badge badge-warning">4</span></td>
                                    <td>Semua aman</td>
                                    <td>
                                        <a href="?mod=patroli&act=edit&id=1" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="?mod=patroli&act=hapus&id=1" class="btn btn-sm btn-outline-danger">Hapus</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal: Tambah Patroli -->
            <div class="modal fade" id="modal-add-patroli" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content rounded shadow-sm">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Patroli</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <ion-icon name="close-outline"></ion-icon>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="form-add-patroli" method="post" action="patroli-save.php" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Tanggal Patroli</label>
                                    <input type="date" class="form-control" name="tanggal_patroli" required>
                                </div>
                                <div class="form-group">
                                    <label>Waktu Patroli</label>
                                    <input type="time" class="form-control" name="waktu_patroli" required>
                                </div>
                                <div class="form-group">
                                    <label>Lokasi</label>
                                    <input type="text" class="form-control" name="lokasi" required>
                                </div>
                                <div class="form-group">
                                    <label>Checklist</label>
                                    <input type="text" class="form-control" name="checklist" placeholder="Contoh: CCTV, Lampu, Pintu" required>
                                </div>
                                <div class="form-group">
                                    <label>Rating (1-5)</label>
                                    <input type="number" class="form-control" name="rating" min="1" max="5" required>
                                </div>
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea class="form-control" name="catatan" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                                </div>
                                <div class="form-group text-right mt-4">
                                    <button type="submit" class="btn btn-primary btn-block">Simpan Patroli</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- * End Modal -->

        </div>
        ';
    }

    include_once 'sw-mod/sw-footer.php';
}
?>
