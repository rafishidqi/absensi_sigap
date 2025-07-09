<?php 

if ($mod == ''){
    header('location:../404');
    echo 'kosong';
} else {
    include_once 'sw-mod/sw-header.php';
    
    if (!isset($_COOKIE['COOKIES_MEMBER'])) {

        $query = mysqli_query($connection, "SELECT max(employees_code) as kodeTerbesar FROM employees");
        $data = mysqli_fetch_array($query);
        $kode_karyawan = $data['kodeTerbesar'];
        $urutan = (int) substr($kode_karyawan, 3, 3);
        $urutan++;
        $huruf = "OM";
        $kode_karyawan = $huruf . sprintf("%03s", $urutan);

        echo '
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
<!-- * App Capsule -->
        ';
    } else {
        header('location:./');
    }

    include_once 'sw-mod/sw-footer.php';
}
?>
