<?php 
if (empty($connection)) {
  header('location:../../');
  exit();
} else {
  include_once 'sw-mod/sw-panel.php';

  echo '<div class="content-wrapper">';

  $op = isset($_GET['op']) ? $_GET['op'] : '';

  switch ($op) {
    case 'tambah':
      echo '
<section class="content-header">
  <h1>Tambah<small> Lokasi Baru</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li><a href="?op">Data Lokasi</a></li>
    <li class="active">Tambah</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Form Tambah Lokasi</b></h3>
        </div>
        <div class="box-body">
          <form method="POST" action="sw-mod/lokasi/proses.php?action=simpan">
            <div class="form-group">
              <label>Nama Lokasi <span class="text-danger">*</span></label>
              <input type="text" name="nama_lokasi" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Deskripsi</label>
              <textarea name="deskripsi_lokasi" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label>Koordinat GPS <small class="text-muted">(klik pada peta)</small></label>
              <input type="text" name="koordinat_gps" id="koordinat_gps" class="form-control" readonly required>
              <div id="map" style="height:400px; margin-top:10px; border:1px solid #ccc;"></div>
            </div>

            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
            <a href="?op" class="btn btn-default">Kembali</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Leaflet Map Library -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  var map = L.map("map").setView([-6.9175, 107.6191], 13); // Default Bandung

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; <a href=\'https://www.openstreetmap.org/\'>OpenStreetMap</a> contributors"
  }).addTo(map);

  var marker;

  function onMapClick(e) {
    var latlng = e.latlng.lat + "," + e.latlng.lng;
    document.getElementById("koordinat_gps").value = latlng;

    if (marker) {
      map.removeLayer(marker);
    }

    marker = L.marker(e.latlng).addTo(map);
  }

  map.on("click", onMapClick);
</script>';
      break;

    case 'edit':
      $id_lokasi = isset($_GET['id']) ? intval($_GET['id']) : 0;
      $query = mysqli_query($connection, "SELECT * FROM tbl_lokasi WHERE id_lokasi = '$id_lokasi'");
      $data = mysqli_fetch_assoc($query);

      if (!$data) {
        echo "<script>alert('Data tidak ditemukan!'); window.location='?op';</script>";
        exit();
      }

      echo '
<section class="content-header">
  <h1>Edit<small> Lokasi</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li><a href="?op">Data Lokasi</a></li>
    <li class="active">Edit</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Form Edit Lokasi</b></h3>
        </div>
        <div class="box-body">
          <form method="POST" action="sw-mod/lokasi/proses.php?action=update">
            <input type="hidden" name="id_lokasi" value="'.$data['id_lokasi'].'">

            <div class="form-group">
              <label>Nama Lokasi <span class="text-danger">*</span></label>
              <input type="text" name="nama_lokasi" class="form-control" required value="'.htmlspecialchars($data['nama_lokasi']).'">
            </div>

            <div class="form-group">
              <label>Deskripsi</label>
              <textarea name="deskripsi_lokasi" class="form-control" rows="3">'.htmlspecialchars($data['deskripsi_lokasi']).'</textarea>
            </div>

            <div class="form-group">
              <label>Koordinat GPS</label>
              <input type="text" name="koordinat_gps" id="koordinat_gps" class="form-control" readonly required value="'.$data['koordinat_gps'].'">
              <div id="map" style="height:400px; margin-top:10px; border:1px solid #ccc;"></div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
            <a href="?op" class="btn btn-default">Kembali</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
  var koordinat = "'.$data['koordinat_gps'].'".split(",");
  var lat = parseFloat(koordinat[0]);
  var lng = parseFloat(koordinat[1]);

  var map = L.map("map").setView([lat, lng], 13);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "&copy; <a href=\'https://www.openstreetmap.org/\'>OpenStreetMap</a> contributors"
  }).addTo(map);

  var marker = L.marker([lat, lng]).addTo(map);

  function onMapClick(e) {
    var latlng = e.latlng.lat + "," + e.latlng.lng;
    document.getElementById("koordinat_gps").value = latlng;

    if (marker) {
      map.removeLayer(marker);
    }

    marker = L.marker(e.latlng).addTo(map);
  }

  map.on("click", onMapClick);
</script>';
      break;


    default:
      echo '
<section class="content-header">
  <h1>Data<small> Lokasi</small></h1>
  <ol class="breadcrumb">
    <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
    <li class="active">Data Lokasi</li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Lokasi</b></h3>
          <div class="box-tools pull-right">
            <a href="?op=tambah" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Tambah Lokasi</a>
          </div>
        </div>
        <div class="box-body">
          <div class="table-responsive">
            <table id="sw-datatable" class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">No</th>
                  <th>Nama Lokasi</th>
                  <th>Deskripsi</th>
                  <th>Koordinat GPS</th>
                  <th class="text-center" style="width:150px">Aksi</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div> 
  </div>
</section>';
      break;
  }

  echo '</div>';
}
?>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables & Delete Logic -->
<script>
$(document).ready(function(){
  $('#sw-datatable').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": "sw-mod/lokasi/sw-datatable-lokasi.php",
    "order": [[0, "desc"]],
    "columns": [
      { "data": 0 },
      { "data": 1 },
      { "data": 2 },
      { "data": 3 },
      { "data": 4 }
    ]
  });

  // Tombol Hapus
  $(document).on("click", ".delete", function(){
    var id = $(this).data("id");
    if(confirm("Yakin ingin menghapus data lokasi ini?")){
      $.post("sw-mod/lokasi/proses.php?action=delete", {id: id}, function(response){
        if(response == "success"){
          alert("Data berhasil dihapus.");
          $('#sw-datatable').DataTable().ajax.reload();
        } else {
          alert("Gagal menghapus data.");
        }
      });
    }
  });
});
</script>
