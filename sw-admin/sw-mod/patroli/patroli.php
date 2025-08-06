patrol
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
  <h1>Data<small> Patroli</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li class="active">Data Patroli</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Patroli</b></h3>
          <div class="box-tools pull-right">';
            // Removed the "Tambah Patroli" button completely
            echo '
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th width="50">No</th>
                  <th>Tanggal</th>
                  <th>Karyawan</th>
                  <th>Lokasi</th>
                  <th>Koordinat GPS</th>
                  <th>Checklist</th>
                  <th>Status</th>
                  <th>Dokumentasi</th>
                  <th>Rating</th>
                  <th>Komentar</th>
                  <th width="150">Aksi</th>
                </tr>
              </thead>
              <tbody>';
            $query = "SELECT p.*, e.employees_name, l.nama_lokasi, l.deskripsi_lokasi, l.koordinat_gps, c.nama_pekerjaan
                                FROM tbl_patroli p
                                LEFT JOIN employees e ON p.id_karyawan = e.id
                                LEFT JOIN tbl_lokasi l ON p.id_lokasi = l.id_lokasi
                                LEFT JOIN tbl_checklist c ON p.id_ceklis = c.id_checklist
                                ORDER BY p.tanggal DESC";
            $result = $connection->query($query);
            if ($result && $result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    // Format tanggal
                    $tanggal = date('d/m/Y H:i', strtotime($row['tanggal']));

                    // Format status dengan label
                    $status_class = '';
                    switch ($row['status']) {
                        case 'Selesai':
                            $status_class = 'label-success';
                            break;
                        case 'Dalam Proses':
                            $status_class = 'label-warning';
                            break;
                        case 'Tertunda':
                            $status_class = 'label-info';
                            break;
                        case 'Dibatalkan':
                            $status_class = 'label-danger';
                            break;
                        default:
                            $status_class = 'label-default';
                    }

                    // Format rating dengan bintang
                    $stars = '';
                    if ($row['rating'] !== null) {
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $row['rating']) {
                                $stars .= '<i class="fa fa-star" style="color: #f39c12;"></i>';
                            } else {
                                $stars .= '<i class="fa fa-star-o" style="color: #ddd;"></i>';
                            }
                        }
                    } else {
                        $stars = '<span class="text-muted">N/A</span>';
                    }

                    // Format dokumentasi
                    $dokumentasi_display = '';
                    if (!empty($row['dokumentasi'])) {
                        $dokumentasi_display = '<a href="../sw-content/patroli/' . $row['dokumentasi'] . '" target="_blank" class="btn btn-xs btn-info">
                                                     <i class="fa fa-image"></i> Lihat
                                                 </a>';
                    } else {
                        $dokumentasi_display = '<span class="text-muted">Tidak ada</span>';
                    }

                    // Format koordinat GPS
                    $koordinat_gps_display = '';
                    if (!empty($row['koordinat_gps'])) {
                        $coords = explode(',', $row['koordinat_gps']);
                        if (count($coords) >= 2) {
                            $lat = trim($coords[0]);
                            $lng = trim($coords[1]);
                            $koordinat_gps_display = '<small>' . $lat . ',<br>' . $lng . '</small>';
                        } else {
                            $koordinat_gps_display = '<small>' . htmlspecialchars($row['koordinat_gps']) . '</small>';
                        }
                    } else {
                        $koordinat_gps_display = '<span class="text-muted">Tidak ada</span>';
                    }

                    echo '<tr>
                                  <td>' . $no++ . '</td>
                                  <td>' . $tanggal . '</td>
                                  <td>' . ($row['employees_name'] ?: 'N/A') . '</td>
                                  <td>' . ($row['nama_lokasi'] ?: 'N/A') . '</td>
                                  <td>' . $koordinat_gps_display . '</td>
                                  <td>' . ($row['nama_pekerjaan'] ?: 'N/A') . '</td>
                                  <td><span class="label ' . $status_class . '">' . htmlspecialchars($row['status']) . '</span></td>
                                  <td>' . $dokumentasi_display . '</td>
                                  <td>' . $stars . '</td>
                                  <td>' . (!empty($row['komentar']) ? htmlspecialchars($row['komentar']) : '<span class="text-muted">Tidak ada</span>') . '</td>
                                  <td>';

                    // Button "Berikan Komentar" (and allow editing rating)
                    // Admins (level 1 or 2) can provide comments and ratings
                    if ($level_user == 1 || $level_user == 2) {
                        echo '<a href="' . $mod . '&op=edit&id=' . $row['id_patroli'] . '" class="btn btn-xs btn-primary"><i class="fa fa-comment"></i> Berikan Komentar</a> ';
                    } else {
                        echo '<button class="btn btn-xs btn-primary access-failed" onclick="alert(\'Anda tidak memiliki akses untuk memberikan komentar atau rating!\')"><i class="fa fa-comment"></i> Berikan Komentar</button> ';
                    }

                    // "Hapus" button (only for level 1 admin)
                    if ($level_user == 1) {
                        echo '<form method="post" action="sw-mod/' . $mod . '/proses.php?action=delete" style="display:inline" onsubmit="return confirm(\'Hapus data ini?\')">
                                        <input type="hidden" name="id" value="' . $row['id_patroli'] . '">
                                        <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                      </form>';
                    } else {
                        echo '<button type="button" class="btn btn-xs btn-danger access-failed" onclick="alert(\'Anda tidak memiliki akses untuk menghapus data!\')"><i class="fa fa-trash"></i> Hapus</button>';
                    }

                    echo '
                                  </td>
                                </tr>';
                }
            } else {
                echo '<tr><td colspan="11" class="text-center">Tidak ada data patroli</td></tr>';
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

        // Removed the "FORM TAMBAH" section completely as per requirement
        // case 'add':
        //    // ... (code for adding patrol, which should be removed)
        //    break;


        // ========== FORM EDIT (for Komentar and Rating) ==========
        case 'edit':
            if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
                $id = (int)$_GET['id'];
                $query = "SELECT p.*, e.employees_name, l.nama_lokasi, l.deskripsi_lokasi, l.koordinat_gps, c.nama_pekerjaan
                                FROM tbl_patroli p
                                LEFT JOIN employees e ON p.id_karyawan = e.id
                                LEFT JOIN tbl_lokasi l ON p.id_lokasi = l.id_lokasi
                                LEFT JOIN tbl_checklist c ON p.id_ceklis = c.id_checklist
                                WHERE p.id_patroli = $id";
                $result = $connection->query($query);
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '
<section class="content-header">
  <h1>Berikan Komentar dan Rating<small> Data Patroli</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li><a href="./' . $mod . '">Data Patroli</a></li>
    <li class="active">Komentar & Rating</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Form Komentar dan Rating Patroli</h3>
        </div>
        <form method="post" action="sw-mod/' . $mod . '/proses.php?action=update_comment_rating" enctype="multipart/form-data">
          <input type="hidden" name="id_patroli" value="' . $row['id_patroli'] . '">
          <div class="box-body">
            <div class="form-group">
              <label>Tanggal Patroli:</label>
              <p class="form-control-static">' . date('d/m/Y H:i', strtotime($row['tanggal'])) . '</p>
            </div>
            <div class="form-group">
              <label>Karyawan:</label>
              <p class="form-control-static">' . ($row['employees_name'] ?: 'N/A') . '</p>
            </div>
            <div class="form-group">
              <label>Lokasi:</label>
              <p class="form-control-static">' . ($row['nama_lokasi'] ?: 'N/A') . '</p>
            </div>
            <div class="form-group">
              <label>Checklist:</label>
              <p class="form-control-static">' . ($row['nama_pekerjaan'] ?: 'N/A') . '</p>
            </div>
            <div class="form-group">
              <label>Status:</label>
              <p class="form-control-static"><span class="label ' . $status_class . '">' . htmlspecialchars($row['status']) . '</span></p>
            </div>
            
            <div class="form-group">
              <label>Rating <span class="text-red">*</span></label>
              <div class="star-rating" id="star-rating-container">
                <span class="star" onclick="setRating(1)" onmouseover="hoverRating(1)" onmouseout="resetHover()" data-value="1">
                  <i class="fa fa-star' . ($row['rating'] >= 1 ? '' : '-o') . '" id="star-1"></i>
                </span>
                <span class="star" onclick="setRating(2)" onmouseover="hoverRating(2)" onmouseout="resetHover()" data-value="2">
                  <i class="fa fa-star' . ($row['rating'] >= 2 ? '' : '-o') . '" id="star-2"></i>
                </span>
                <span class="star" onclick="setRating(3)" onmouseover="hoverRating(3)" onmouseout="resetHover()" data-value="3">
                  <i class="fa fa-star' . ($row['rating'] >= 3 ? '' : '-o') . '" id="star-3"></i>
                </span>
                <span class="star" onclick="setRating(4)" onmouseover="hoverRating(4)" onmouseout="resetHover()" data-value="4">
                  <i class="fa fa-star' . ($row['rating'] >= 4 ? '' : '-o') . '" id="star-4"></i>
                </span>
                <span class="star" onclick="setRating(5)" onmouseover="hoverRating(5)" onmouseout="resetHover()" data-value="5">
                  <i class="fa fa-star' . ($row['rating'] >= 5 ? '' : '-o') . '" id="star-5"></i>
                </span>
              </div>
              <input type="hidden" name="rating" id="rating-value" value="' . ($row['rating'] ?: '') . '" required>
              <small class="text-muted">Klik bintang untuk memberikan rating</small>
              <div id="rating-display" style="margin-top: 5px; font-weight: bold; color: #666;">
                Rating: <span id="rating-text">' . ($row['rating'] ? $row['rating'] . ' bintang' : 'Belum dipilih') . '</span>
              </div>
            </div>
            
            <div class="form-group">
              <label>Komentar</label>
              <textarea name="komentar" class="form-control" rows="4" placeholder="Masukkan komentar atau catatan...">' . htmlspecialchars($row['komentar']) . '</textarea>
            </div>
          </div>
          <div class="box-footer">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Komentar/Rating</button>
            <a href="./' . $mod . '" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>';
                } else {
                    echo '<div class="content-wrapper">
                                <section class="content">
                                  <div class="alert alert-danger">
                                    <h4><i class="fa fa-ban"></i> Error!</h4>
                                    Data patroli dengan ID ' . $id . ' tidak ditemukan atau sudah dihapus.
                                    <br><br>
                                    <a href="./' . $mod . '" class="btn btn-default">
                                      <i class="fa fa-arrow-left"></i> Kembali ke Data Patroli
                                    </a>
                                  </div>
                                </section>
                              </div>';
                }
            } else {
                echo '<div class="content-wrapper">
                            <section class="content">
                              <div class="alert alert-danger">
                                <h4><i class="fa fa-ban"></i> Error!</h4>
                                ID patroli tidak valid atau tidak ditemukan.
                                <br><br>
                                <a href="./' . $mod . '" class="btn btn-default">
                                  <i class="fa fa-arrow-left"></i> Kembali ke Data Patroli
                                </a>
                              </div>
                            </section>
                          </div>';
            }
            break;
    }

    echo '</div>';

    // JavaScript for star rating
    echo '
<script type="text/javascript">
// Star Rating Functions
function setRating(rating) {
    console.log("Setting rating to:", rating);

    // Update hidden input
    document.getElementById("rating-value").value = rating;

    // Update rating text
    document.getElementById("rating-text").innerText = rating + " bintang";

    // Update star display
    for (var i = 1; i <= 5; i++) {
        var star = document.getElementById("star-" + i);
        if (star) {
            if (i <= rating) {
                star.className = "fa fa-star";
                star.style.color = "#f39c12";
            } else {
                star.className = "fa fa-star-o";
                star.style.color = "#ddd";
            }
        }
    }

    // Remove error styling if exists
    var container = document.getElementById("star-rating-container");
    if (container) {
        container.classList.remove("error-rating");
    }
}

function hoverRating(rating) {
    for (var i = 1; i <= 5; i++) {
        var star = document.getElementById("star-" + i);
        if (star) {
            if (i <= rating) {
                star.style.color = "#f39c12";
            } else {
                star.style.color = "#ddd";
            }
        }
    }
}

function resetHover() {
    var currentRating = document.getElementById("rating-value").value || 0;
    for (var i = 1; i <= 5; i++) {
        var star = document.getElementById("star-" + i);
        if (star) {
            if (i <= currentRating) {
                star.style.color = "#f39c12";
            } else {
                star.style.color = "#ddd";
            }
        }
    }
}

// Initialize rating display on page load for edit form
document.addEventListener("DOMContentLoaded", function() {
    var initialRating = document.getElementById("rating-value") ? document.getElementById("rating-value").value : 0;
    if (initialRating) {
        setRating(parseInt(initialRating));
    }
});

// Access control handled inline
</script>

<style>
.star-rating {
    font-size: 24px;
    margin: 10px 0;
}
.star {
    cursor: pointer;
    margin-right: 5px;
    transition: all 0.2s ease;
    display: inline-block;
}
.star:hover {
    transform: scale(1.1);
}
.star i {
    color: #ddd;
    transition: color 0.2s ease;
}
.star.active i,
.star.hover i {
    color: #f39c12;
}
.star:hover i {
    color: #f39c12;
}
.star-rating .star:last-child {
    margin-right: 0;
}
.error-rating {
    border: 2px dashed #dd4b39;
    border-radius: 5px;
    padding: 5px;
    background-color: rgba(221, 75, 57, 0.1);
}
.error-rating .star i {
    color: #dd4b39 !important;
}
</style>';
}
?>