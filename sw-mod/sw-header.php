<?php if(empty($connection)){
  header('location:./404');
} else {
  ob_start("minify_html");


echo'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
  <title>'.$website_name.'</title> <!-- Sementara pakai hardcode karna belom ada urlbase baru -->
  <meta name="theme-color" content="#FF396F">
  <meta name="msapplication-navbutton-color" content="#FF396F">
  <meta name="apple-mobile-web-app-status-bar-style" content="#FF396F">

    <!-- Favicons -->
  <link rel="shortcut icon" href="'.$base_url.'/sw-content/SPM_SIAGA2.png">
  <link rel="apple-touch-icon" href="'.$base_url.'/sw-content/SPM_SIAGA2.png">
  <link rel="apple-touch-icon" sizes="72x72" href="'.$base_url.'/sw-content/SPM_SIAGA2.png">
  <link rel="apple-touch-icon" sizes="114x114" href="'.$base_url.'/sw-content/SPM_SIAGA2.png">
  
  <meta name="robots" content="noindex">
  <meta name="description" content="'.$meta_description.'">
  <meta name="keywords" content="'.$meta_keyword.'">
  <meta name="author" content="'.$website_name.'">
  <meta http-equiv="Copyright" content="'.$website_name.'">
  <meta name="copyright" content="'.$website_name.'">
  <meta itemprop="image" content="sw-content/meta-tag.jpg">

  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/css/style.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/css/sw-custom.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/js/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/js/plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="'.$base_url.'/sw-mod/sw-assets/js/plugins/magnific-popup/magnific-popup.css">

  <script src="'.$base_url.'/sw-mod/sw-assets/js/lib/jquery-3.4.1.min.js"></script>
  <script src="'.$base_url.'/sw-mod/sw-assets/js/lib/bootstrap.min.js"></script>

  
  
</head>';

if($mod=='home'){
echo'<body onload="loadDataCounter();">';
}elseif ($mod=='cuty') {
echo'<body onload="loadDataCuty();">';
}elseif($mod=='izin'){
echo'<body onload="loadDataIzin();">';
}elseif ($mod=='history') {
echo'<body onload="loadData();">';
}else{
echo'<body>';
}
echo'

<body>

<!-- Modal Setting -->
<div class="modal fade" id="settingModal" tabindex="-1" role="dialog" aria-labelledby="settingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingModalLabel">Pengaturan Logo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-ganti-logo" enctype="multipart/form-data">
            <div class="form-group">
                <label for="theme_color">Pilih Warna Tema</label>
                <input type="color" class="form-control" id="theme_color" name="theme_color" value="'.$theme_color.'">
            </div>
            <div class="form-group">
                <label for="logo">Pilih Logo Baru</label>
                <input type="file" class="form-control-file" id="logo" name="logo">
            </div>
            
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- * Modal Setting -->
<div class="loading"><div class="spinner-border text-primary" role="status"></div></div>
  <!-- loader -->
    <div id="loader">
        <img src="'.$base_url.'sw-content/'.$site_logo.'" alt="icon" class="loading-icon" style="max-width: 100%; max-height: 100%; height: auto; width: auto; display: block; margin: 0 auto;">
    </div>
    <!-- * loader -->';
if(isset($_COOKIE['COOKIES_MEMBER'])){
  echo'
<!-- App Header -->
    <div class="appHeader bg-danger text-light">
        <div class="left">
            <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="'.$base_url.'sw-content/'.$site_logo.'" alt="logo" class="logo">
        </div>
        <div class="right">
            <div class="headerButton" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true">';
              if($row_user['photo'] ==''){
                echo'<img src="'.$base_url.'sw-content/avatar.jpg" alt="image" class="imaged w32">';
              }else{
                echo'
                <img src="timthumb?src='.$base_url.'sw-content/karyawan/'.$row_user['photo'].'&h=40&w=45" alt="image" class="imaged w32">';}
              echo'
               <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';?>
                <a class="dropdown-item" onclick="location.href='./profile';" href="./profile"><ion-icon size="small" name="person-outline"></ion-icon>Profil</a>
                <a class="dropdown-item" href="#" id="open-setting-modal">
                    <ion-icon size="small" name="settings-outline"></ion-icon> Setting
                </a>
                <a class="dropdown-item" onclick="location.href='./logout';" href="./logout"><ion-icon size="small" name="log-out-outline"></ion-icon>Keluar</a>
              </div>
            </div>
        </div>
            <div class="progress" style="display:none;position:absolute;top:50px;z-index:4;left:0px;width: 100%">
                <div id="progressBar" class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">0%</span>
                </div>
            </div>
    </div>
<?php
echo'<!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- profile box -->
                    <div class="profileBox pt-2 pb-2">
                        <div class="image-wrapper">';
                        if($row_user['photo'] ==''){
                        echo'<img src="'.$base_url.'/sw-content/avatar.jpg" alt="image" class="imaged  w36">';
                        }else{
                        echo'<img src="timthumb?src='.$base_url.'sw-content/karyawan/'.$row_user['photo'].'&h=40&w=45" class="imaged  w36">';
                        }
                          echo'
                        </div>
                        <div class="in">
                            <strong>'.ucfirst($row_user['employees_name']).'</strong>
                            <div class="text-muted">'.$row_user['employees_code'].'</div>
                        </div>
                        <a href="#" class="btn btn-link btn-icon sidebar-close" data-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                    <!-- * profile box -->
              
                    <!-- menu -->
                    <div class="listview-title mt-1">Absen</div>
                    <ul class="listview flush transparent no-line image-listview">
                        <li>
                            <a href="./" class="item">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="home-outline"></ion-icon>
                                </div> Home 
                            </a>
                        </li>
                        <li>
                            <a href="./absent" class="item">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="scan-outline"></ion-icon>
                                </div>
                                    Absen
                            </a>
                        </li>

                        <li>
                            <a href="./izin" class="item">
                                <div class="icon-box bg-danger">
                                   <ion-icon name="documents-outline"></ion-icon>
                                </div>
                                  Izin
                            </a>
                        </li>


                        <li>
                            <a href="./cuty" class="item">
                                <div class="icon-box bg-danger">
                                  <ion-icon name="calendar-outline"></ion-icon>
                                </div>
                                  Cuti
                            </a>
                        </li>

                        <li>
                            <a href="./history" class="item">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="document-text-outline"></ion-icon>
                                </div>
                                   History
                            </a>
                        </li>
                      
                        <li>
                            <a href="profile" class="item">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="person-outline"></ion-icon>
                                </div>
                                    Profil
                            </a>
                        </li>

                        <li>
                            <a href="setting" class="item">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="settings-outline"></ion-icon>
                                </div>
                                    setting
                            </a>
                        </li>

                        </li>
                        <li>
                            <a href="./logout" class="item">
                                <div class="icon-box bg-danger">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                </div>
                                    Keluar
                            </a>
                        </li>

                    </ul>
                    <!-- * menu -->
                </div>
            </div>
        </div>
    </div>
    <!-- * App Sidebar -->';
  }
 }?>

<!-- Script jQuery untuk buka modal -->
<script>
  $(document).ready(function () {
    $('#open-setting-modal').on('click', function (e) {
      e.preventDefault();
      $('#settingModal').modal('show');
    });
  });

  $('#form-ganti-logo').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
      url: 'sw-mod/upload_logo.php',
      type: 'POST',
      data: formData,
      success: function (res) {
        var data = JSON.parse(res);
        alert(data.message);
        if (data.status === 'success') {
          location.reload(); // agar logo baru langsung tampil
        }
      },
      cache: false,
      contentType: false,
      processData: false
    });
  });
</script>


<style>
  :root {
    --main-theme: <?php echo $theme_color; ?>;
  }

  .bg-danger {
    background-color: var(--main-theme) !important;
  }

  .icon-box.bg-danger {
    background-color: var(--main-theme) !important;
  }

  .appHeader.bg-danger {
    background-color: var(--main-theme) !important;
  }
</style>
