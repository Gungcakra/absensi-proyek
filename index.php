

<?php
require_once "./library/config.php";
session_start();

// Batasi percobaan login
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= 3) {
    die("Try Again Later.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    
    if (empty($username) || empty($password)) {
        $error = "Username dan password tidak boleh kosong.";
    } else {
      
        $user = query("SELECT * FROM user WHERE username = ?", [$username]);

        if ($user && password_verify($password, $user[0]['password'])) {
            $_SESSION['userId'] = $user[0]['userId'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['login_attempts'] = 0; 
            header('Location: '. BASE_URL_HTML .'/system/');
            exit();
        } else {
            $_SESSION['login_attempts']++;
            $error = "Username atau password salah.";
        }
    }
}



?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title><?= PAGE_TITLE ?></title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="<?= BASE_URL_HTML ?>/assets/images/favicon.ico" />
      <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/css/backend-plugin.min.css">
      <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/css/backend.css?v=1.0.0">
      <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
      <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/remixicon/fonts/remixicon.css">
      
      <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-calendar/dist/tui-calendar.css">
      <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-date-picker/dist/tui-date-picker.css">
      <link rel="stylesheet" href="<?= BASE_URL_HTML ?>/assets/vendor/tui-calendar/tui-time-picker/dist/tui-time-picker.css">  </head>
  <body class=" ">
    <!-- loader Start -->
    <div id="loading">
          <div id="loading-center">
          </div>
    </div>
    <!-- loader END -->
    
      <div class="wrapper">
      <section class="login-content">
         <div class="container">
            <div class="row align-items-center justify-content-center height-self-center">
               <div class="col-lg-8">
                  <h2 class="text-center text-primary">Absensi Proyek </h2>
                  <div class="card auth-card">
                     <div class="card-body p-0">
                        <div class="d-flex align-items-center auth-content">
                           <div class="col-lg-6 bg-primary content-left">
                              <div class="p-3">
                                 <h2 class="mb-2 text-white">Sign In</h2>
                                 <p>Login to stay connected.</p>
                                 <form action="" method="POST">
                                    <div class="row">
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                             <input class="floating-input form-control" type="text" placeholder=" " name="username" id="username" autocomplete="off">
                                             <label>Username</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-12">
                                          <div class="floating-label form-group">
                                             <input class="floating-input form-control" type="password" placeholder=" " name="password" id="password" autocomplete="off">
                                             <label>Password</label>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <!-- <div class="custom-control custom-checkbox mb-3">
                                             <input type="checkbox" class="custom-control-input" id="customCheck1">
                                             <label class="custom-control-label control-label-1 text-white" for="customCheck1">Remember Me</label>
                                          </div> -->
                                       </div>
                                       <div class="col-lg-6">
                                          <!-- <a href="auth-recoverpw.html" class="text-white float-right">Forgot Password?</a> -->
                                       </div>
                                    </div>
                                    <button type="submit" class="btn btn-white">Sign In</button>
                                    <!-- <p class="mt-3">
                                       Create an Account <a href="auth-sign-up.html" class="text-white text-underline">Sign Up</a>
                                    </p> -->
                                 </form>
                              </div>
                           </div>
                           <div class="col-lg-6 content-right">
                              <img src="<?= BASE_URL_HTML ?>/assets/images/login/01.png" class="img-fluid image-right" alt="">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      </div>
    
    <!-- Backend Bundle JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/backend-bundle.min.js"></script>
    
    <!-- Table Treeview JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/table-treeview.js"></script>
    
    <!-- Chart Custom JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/customizer.js"></script>
    
    <!-- Chart Custom JavaScript -->
    <script async src="<?= BASE_URL_HTML ?>/assets/js/chart-custom.js"></script>
    <!-- Chart Custom JavaScript -->
    <script async src="<?= BASE_URL_HTML ?>/assets/js/slider.js"></script>
    
    <!-- app JavaScript -->
    <script src="<?= BASE_URL_HTML ?>/assets/js/app.js"></script>
    
    <script src="<?= BASE_URL_HTML ?>/assets/vendor/moment.min.js"></script>
  </body>
</html>