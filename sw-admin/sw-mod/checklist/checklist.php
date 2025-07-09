<?php
if (empty($connection)) {
    header('location:../../');
} else {
    include_once 'sw-mod/sw-panel.php';
    echo '<div class="content-wrapper">';

    switch (@$_GET['op']) {

        // ========== HALAMAN UTAMA ==========
        default:
            echo '
<section class="content-header">
  <h1>Data<small> Checklist</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li class="active">Data Checklist</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Checklist</b></h3>
          <div class="box-tools pull-right">';
            if ($level_user == 1) {
                echo '<a href="' . $mod . '&op=add" class="btn btn-success btn-flat"><i class="fa fa-plus"></i> Tambah Checklist</a>';
            } else {
                echo '<button type="button" class="btn btn-success btn-flat access-failed"><i class="fa fa-plus"></i> Tambah Checklist</button>';
            }
            echo '
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th width="50">No</th>
                  <th>Nama Pekerjaan</th>
                  <th>Deskripsi</th>
                  <th>Kategori</th>
                  <th width="150">Aksi</th>
                </tr>
              </thead>
              <tbody>';
            $query = "SELECT * FROM tbl_checklist ORDER BY id_checklist DESC";
            $result = $connection->query($query);
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                      <td>' . $no++ . '</td>
                      <td>' . htmlspecialchars($row['nama_pekerjaan']) . '</td>
                      <td>' . nl2br(htmlspecialchars($row['deskripsi_pekerjaan'])) . '</td>
                      <td>' . htmlspecialchars($row['kategori_pekerjaan']) . '</td>
                      <td>
                        <a href="' . $mod . '&op=edit&id=' . $row['id_checklist'] . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>
                        <form method="post" action="sw-mod/checklist/proses.php?action=delete" style="display:inline" onsubmit="return confirm(\'Hapus data ini?\')">
                          <input type="hidden" name="id" value="' . epm_encode($row['id_checklist']) . '">
                          <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>';
                }
            } else {
                echo '<tr><td colspan="5" class="text-center">Belum ada data checklist.</td></tr>';
            }
            echo '
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>';
            break;

        // ========== HALAMAN TAMBAH ==========
        case 'add':
            echo '
<section class="content-header">
  <h1>Tambah<small> Checklist</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li><a href="./checklist">Data Checklist</a></li>
    <li class="active">Tambah</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Form Tambah Checklist</h3>
        </div>
        <form method="post" action="sw-mod/checklist/proses.php?action=add">
          <div class="box-body">
            <div class="form-group">
              <label>Nama Pekerjaan</label>
              <input type="text" name="nama_pekerjaan" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Deskripsi Pekerjaan</label>
              <textarea name="deskripsi_pekerjaan" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
              <label>Kategori Pekerjaan</label>
              <select name="kategori_pekerjaan" class="form-control" required>
                <option value="">- Pilih Kategori -</option>
                <option value="Harian">Harian</option>
                <option value="Mingguan">Mingguan</option>
                <option value="Bulanan">Bulanan</option>
                <option value="Insidental">Insidental</option>
              </select>
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
            <a href="./checklist" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>';
            break;

        // ========== HALAMAN EDIT ==========
        case 'edit':
            if (!empty($_GET['id'])) {
                $id = mysqli_real_escape_string($connection, $_GET['id']);
                $query = "SELECT * FROM tbl_checklist WHERE id_checklist = '$id'";
                $result = $connection->query($query);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '
<section class="content-header">
  <h1>Edit<small> Checklist</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li><a href="./checklist">Data Checklist</a></li>
    <li class="active">Edit</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Form Edit Checklist</h3>
        </div>
        <form method="post" action="sw-mod/checklist/proses.php?action=update">
          <div class="box-body">
            <input type="hidden" name="id" value="' . $row['id_checklist'] . '">
            <div class="form-group">
              <label>Nama Pekerjaan</label>
              <input type="text" name="nama_pekerjaan" class="form-control" value="' . htmlspecialchars($row['nama_pekerjaan']) . '" required>
            </div>
            <div class="form-group">
              <label>Deskripsi Pekerjaan</label>
              <textarea name="deskripsi_pekerjaan" class="form-control" rows="4" required>' . htmlspecialchars($row['deskripsi_pekerjaan']) . '</textarea>
            </div>
            <div class="form-group">
              <label>Kategori Pekerjaan</label>
              <select name="kategori_pekerjaan" class="form-control" required>
                <option value="">- Pilih Kategori -</option>
                <option value="Harian" ' . ($row['kategori_pekerjaan'] == 'Harian' ? 'selected' : '') . '>Harian</option>
                <option value="Mingguan" ' . ($row['kategori_pekerjaan'] == 'Mingguan' ? 'selected' : '') . '>Mingguan</option>
                <option value="Bulanan" ' . ($row['kategori_pekerjaan'] == 'Bulanan' ? 'selected' : '') . '>Bulanan</option>
                <option value="Insidental" ' . ($row['kategori_pekerjaan'] == 'Insidental' ? 'selected' : '') . '>Insidental</option>
              </select>
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Simpan</button>
            <a href="./checklist" class="btn btn-danger"><i class="fa fa-times"></i> Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>';
                } else {
                    echo '<div class="alert alert-danger">Data tidak ditemukan. <a href="./checklist" class="btn btn-sm btn-default">Kembali</a></div>';
                }
            }
            break;
    }

    echo '</div>';
}