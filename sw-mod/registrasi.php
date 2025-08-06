<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER']) OR !isset($_COOKIE['COOKIES_COOKIES'])){
 echo'
 <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section text-center">
            <h1>Mendaftar</h1>
        </div>
        <div class="section mb-5 p-2">
            <form id="form-registrasi">
                <div class="card">
                    <div class="card-body pb-1">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">NIP</label>
                                <input type="text" class="form-control" id="employees_code" name="employees_code" required>
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Nama</label>
                                <input type="text" class="form-control" id="name" name="employees_name" required>
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="employees_email" required>
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Jabatan</label>
                                <select class="form-control" name="position_id" id="position_id"  required="">
                                  <option value="">- Pilih -</option>';
                                  $query="SELECT * from position order by position_name ASC";
                                  $result = $connection->query($query);
                                  while($row = $result->fetch_assoc()) { 
                                  echo'<option value="'.$row['position_id'].'">'.$row['position_name'].'</option>';
                                  }echo'
                                </select>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Jam Kerja</label>
                                <select class="form-control" name="shift_id" id="shift_id" required="">
                                  <option value="">- Pilih -</option>';
                                  $query="SELECT shift_id,shift_name from shift order by shift_name ASC";
                                  $result = $connection->query($query);
                                  while($row = $result->fetch_assoc()) { 
                                  echo'<option value="'.$row['shift_id'].'">'.$row['shift_name'].'</option>';
                                  }echo'
                                </select>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Lokasi</label>
                                <select class="form-control" name="building_id" id="building" required="">
                                  <option value="">- Pilih -</option>';
                                  $query="SELECT building_id,name,address from building order by name ASC";
                                  $result = $connection->query($query);
                                  while($row = $result->fetch_assoc()) { 
                                  echo'<option value="'.$row['building_id'].'">'.$row['name'].'</option>';
                                  }echo'
                              </select>
                            </div>
                        </div>


        
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="password1">Password</label>
                                <input type="password" class="form-control" id="password" name="employees_password" placeholder="Passworb baru">
                                <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-links mt-2">
                    <div>
                        <a href=./>Sudah punya akun?</a>
                    </div>
                    <div><a href="forgot" class="text-muted">Lupa Password?</a></div>
                </div>

                <div class="form-button-group transparent">
                   <button type="submit" class="btn btn-danger btn-block btn-lg">Mendaftar</button>
                </div>

            </form>
        </div>

    </div>
    <!-- * App Capsule -->';}
  else{
  }

  include_once 'sw-mod/sw-footer.php';
} 
if ($mod == '') {
    header('location:../404');
    echo 'kosong';
} else {
    include_once 'sw-mod/sw-header.php';

    if (!isset($_COOKIE['COOKIES_MEMBER']) OR !isset($_COOKIE['COOKIES_COOKIES'])) {

        echo '
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section text-center">
        <h2 class="mb-1">Daftar Akun Baru</h2>
        <p class="text-muted">Isi form di bawah ini untuk membuat akun</p>
    </div>

    <div class="section mb-5 px-3">
        <div class="container">
            <div class="mx-auto" style="max-width: 500px;">
                <form id="form-registrasi">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">

                            <div class="form-group mb-3">
                                <label for="employees_code">NIP</label>
                                <input type="text" class="form-control" id="employees_code" name="employees_code" placeholder="Masukkan NIP" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="name">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="employees_name" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="employees_email" placeholder="Masukkan email aktif" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="position_id">Jabatan</label>
                                <select class="form-control" name="position_id" id="position_id" required>
                                    <option value="">- Pilih Jabatan -</option>';
                                    $query = "SELECT * FROM position ORDER BY position_name ASC";
                                    $result = $connection->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="'.$row['position_id'].'">'.$row['position_name'].'</option>';
                                    }
                                    echo '
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="shift_id">Jam Kerja</label>
                                <select class="form-control" name="shift_id" id="shift_id" required>
                                    <option value="">- Pilih Jam Kerja -</option>';
                                    $query = "SELECT shift_id, shift_name FROM shift ORDER BY shift_name ASC";
                                    $result = $connection->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="'.$row['shift_id'].'">'.$row['shift_name'].'</option>';
                                    }
                                    echo '
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="building">Lokasi</label>
                                <select class="form-control" name="building_id" id="building" required>
                                    <option value="">- Pilih Lokasi -</option>';
                                    $query = "SELECT building_id, name FROM building ORDER BY name ASC";
                                    $result = $connection->query($query);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="'.$row['building_id'].'">'.$row['name'].'</option>';
                                    }
                                    echo '
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="employees_password" placeholder="Buat password" required>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-block w-100">Daftar Sekarang</button>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p>Sudah punya akun? <a href="./" class="text-primary">Masuk</a></p>
                        <p><a href="forgot" class="text-muted">Lupa Password?</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- * App Capsule -->
        ';
    }

    include_once 'sw-mod/sw-footer.php';
}
?>
