<?php 
if ($mod ==''){
    header('location:../404');
    echo'kosong';
}else{
    include_once 'sw-mod/sw-header.php';
if(!isset($_COOKIE['COOKIES_MEMBER'])){
 echo'
 <!-- App Capsule -->
    <div id="appCapsule">

    <div class="section mt-2 text-center">
        <h2 class="mb-1">Selamat Datang Kembali</h2>
        <p class="text-muted">Masukkan email dan password Anda untuk masuk</p>
    </div>

    <div class="section mb-5 px-3">
        <div class="container">
            <div class="mx-auto" style="max-width: 500px;">
                <form id="form-login">
                    <div class="card shadow-sm border-0">
                        <div class="card-body pb-1">

                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail Anda" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Kata sandi Anda" required>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-block w-100">
                                    <ion-icon name="log-in-outline"></ion-icon> Masuk
                                </button>
                                <a href="oauth/google" class="btn btn-danger btn-block w-100 mt-2">
                                    <ion-icon name="logo-google"></ion-icon> Masuk dengan Google
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <p>Belum punya akun? <a href="registrasi" class="text-primary">Daftar</a></p>
                        <p><a href="forgot" class="text-muted">Lupa Password?</a></p>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
    <!-- * App Capsule -->';
}
  else{

  echo'<!-- App Capsule -->
    <div id="appCapsule">
        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-1">
            <div class="wallet-card">
                <!-- Balance -->
                <div class="balance">
                    <div class="left">
                        <span class="title"> Selamat '.$salam.'</span>
                        <h1 class="total">'.ucfirst($row_user['employees_name']).'</h1>
                    </div>';
                if($result_shift->num_rows > 0){
                    $row_shift = $result_shift->fetch_assoc();
                    echo'
                    <div class="left">
                        <h4>Jam Kerja</h4>
                        <p>'.$row_shift['time_in'].' - '.$row_shift['time_out'].'</p>
                    </div>';
                }
                echo'
                </div>
                <!-- * Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer">
                    <div class="item">
                        <a href="./absent">
                            <div class="icon-wrapper bg-danger">
                                <ion-icon name="camera-outline"></ion-icon>
                            </div>
                            <strong>Absen</strong>
                        </a>
                    </div>

                    <div class="item">
                        <a href="./izin">
                            <div class="icon-wrapper bg-warning">
                               <ion-icon name="documents-outline"></ion-icon>
                            </div>
                            <strong>Izin</strong>
                        </a>
                    </div>

                    <div class="item">
                        <a href="./cuty">
                            <div class="icon-wrapper bg-primary">
                               <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <strong>Cuti</strong>
                        </a>
                    </div>
                   
                    <div class="item">
                        <a href="./history">
                            <div class="icon-wrapper bg-success">
                               <ion-icon name="document-text-outline"></ion-icon>
                            </div>
                            <strong>History</strong>
                        </a>
                    </div>

                    <div class="item">
                        <a href="./profile">
                            <div class="icon-wrapper bg-warning">
                               <ion-icon name="person-outline"></ion-icon>
                            </div>
                            <strong>Profil</strong>
                        </a>
                    </div>


                </div>
                <!-- * Wallet Footer -->
            </div>
        </div>
        <!-- Wallet Card -->

    <!-- Label Absensi Hari ini -->
    <div class="section">
        <div class="row mt-2">';
            if($result_absent->num_rows > 0){
                $row_absent     = $result_absent->fetch_assoc();
                echo'
                <div class="col-6">
                    <div class="stat-box bg-danger">
                        <div class="title text-white">Absen Masuk</div>
                        <div class="value text-white">'.$row_absent['time_in'].'</div>
                    </div>
                </div>';

                if($row_absent['time_out']=='00:00:00'){
                echo'
                <div class="col-6">
                    <a href="./absent"><div class="stat-box bg-success">
                        <div class="title text-white">Absen Pulang</div>
                        <div class="value text-white">Belum absen</div>
                    </div></a>
                </div>';
                }else{
                echo'
                <div class="col-6">
                    <div class="stat-box bg-success">
                        <div class="title text-white">Absen Pulang</div>
                        <div class="value text-white">'.$row_absent['time_out'].'</div>
                    </div>
                </div>';}
            } 
            else{
                echo'
                <div class="col-6">
                    <a href="./absent"><div class="stat-box bg-danger">
                        <div class="title text-white">Absen Masuk</div>
                        <div class="value text-white">Belum absen</div>
                    </div></a>
                </div>

                <div class="col-6">
                    <div class="stat-box bg-secondary">
                        <div class="title text-white">Absen Pulang</div>
                        <div class="value text-white">Belum Absen</div>
                    </div>
                </div>
                ';
            }   
        echo' 
        </div>
    </div>


    <div class="section mt-4">
        <div class="section-title mb-1">Absensi Bulan
            <select class="select select-change text-primary" required>';
                if($month ==1){echo'<option value="01" selected>Januari</option>';}else{echo'<option value="01">Januari</option>';}
                if($month ==2){echo'<option value="02" selected>Februari</option>';}else{echo'<option value="02">Februari</option>';}
                if($month ==3){echo'<option value="03" selected>Maret</option>';}else{echo'<option value="03">Maret</option>';}
                if($month ==4){echo'<option value="04" selected>April</option>';}else{echo'<option value="04">April</option>';}
                if($month ==5){echo'<option value="05" selected>Mei</option>';}else{echo'<option value="05">Mei</option>';}
                if($month ==6){echo'<option value="06" selected>Juni</option>';}else{echo'<option value="06">Juni</option>';}
                if($month ==7){echo'<option value="07" selected>Juli</option>';}else{echo'<option value="07">Juli</option>';}
                if($month ==8){echo'<option value="08" selected>Agustus</option>';}else{echo'<option value="08">Agustus</option>';}
                if($month ==9){echo'<option value="09" selected>September</option>';}else{echo'<option value="09">September</option>';}
                if($month ==10){echo'<option value="10" selected>Oktober</option>';}else{echo'<option value="10">Oktober</option>';}
                if($month ==11){echo'<option value="12" selected>November</option>';}else{echo'<option value="12">November</option>';}
                if($month ==12){echo'<option value="12" selected>Desember</option>';}else{echo'<option value="12">Desember</option>';}
              echo'
            </select><span class="text-primary">'.$year.'</span>
        </div>
        <div class="transactions">
            <div class="row">
                <div class="load-home" style="display:contents"></div>   
            </div>
            </div>
        </div>

      <div class="section mt-2 mb-2">
            <div class="section-title">1 Minggu Terakhir</div>
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-dark rounded bg-danger">
                        <thead>
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Jam Masuk</th>
                                <th scope="col">Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $query_absen="SELECT presence_date,time_in,time_out FROM presence WHERE YEARWEEK(presence_date)=YEARWEEK(NOW()) AND employees_id='$row_user[id]' ORDER BY presence_id DESC LIMIT 6";
                        $result_absen = $connection->query($query_absen);
                        if($result_absen->num_rows > 0){
                            while ($row_absen= $result_absen->fetch_assoc()) {
                            echo'
                            <tr>
                                <th scope="row">'.tgl_ind($row_absen['presence_date']).'</th>
                                <td>'.$row_absen['time_in'].'</td>
                                <td>'.$row_absen['time_out'].'</td>
                            </tr>';
                        }}
                        echo'
                        </tbody>
                    </table>
                </div>
            </div>
        </div>   
    </div>';

    }
  include_once 'sw-mod/sw-footer.php';
} ?>