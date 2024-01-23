<?php
session_start();
include('config/config.php');
require_once('config/code-generator.php');

if (isset($_POST['reset_pwd'])) {
  if (!filter_var($_POST['reset_email'], FILTER_VALIDATE_EMAIL)) {
    $err = 'Invalid Email';
  }
  $checkEmail = mysqli_query($mysqli, "SELECT `admin_email` FROM `rpos_admin` WHERE `admin_email` = '" . $_POST['reset_email'] . "'") or exit(mysqli_error($mysqli));
  if (mysqli_num_rows($checkEmail) > 0) {
    //exit('This email is already being used');
    //Reset Password
    $reset_code = $_POST['reset_code'];
    $reset_token = sha1(md5($_POST['reset_token']));
    $reset_status = $_POST['reset_status'];
    $reset_email = $_POST['reset_email'];
    $query = "INSERT INTO rpos_pass_resets (reset_email, reset_code, reset_token, reset_status) VALUES (?,?,?,?)";
    $reset = $mysqli->prepare($query);
    $rc = $reset->bind_param('ssss', $reset_email, $reset_code, $reset_token, $reset_status);
    $reset->execute();
    if ($reset) {
      $success = "Password Reset Instructions Sent To Your Email";
      // && header("refresh:1; url=index.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  } else {
    $err = "No account with that email";
  }
}
require_once('partials/_head.php');
?>

<body class="bg-warning">
  <div>
    <div class="main-content">
      <div class="header bg-gradient-primar py-7">
        <div class="container">
          <div class="header-body text-center mb-7">
            <div class="row justify-content-center">
              <div class="col-lg-5 col-md-6">
              <h1 class="text-white"><a style="color:white" href="../../index.php">Elaine, Lee, and Jecie's</a></h1>
              <h1 class="text-white"><a style="color:white" href="../../index.php">Carinderia</a></h1>
              </div>
            </div>
          </div>
        </div>
      </div>
<!-- Page content -->
<div class="container mt--8 pb-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      <div class="card bg-yellow shadow border-0">
        <div class="card-body px-lg-5 py-lg-5">
          <form method="post" role="form">
            <!-- Email input fields -->
            <div class="form-group mb-3">
              <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                </div>
                <input class="form-control" required name="reset_email" placeholder="Email" type="email">
              </div>
            </div>
            <!-- Add password and Repeat password fields -->
            <div class="form-group mb-3">
              <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                </div>
                <input class="form-control" required name="reset_password" placeholder="Password" type="password">
              </div>
            </div>

            <div class="form-group mb-3">
              <div class="input-group input-group-alternative">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                </div>
                <input class="form-control" required name="reset_password_repeat" placeholder="Repeat Password" type="password">
              </div>
            </div>

            <div style="display:none">
              <input type="text" value="<?php echo $tk; ?>" name="reset_token">
              <input type="text" value="<?php echo $rc; ?>" name="reset_code">
              <input type="text" value="Pending" name="reset_status">
            </div>

            <div class="text-center">
              <button type="submit" name="reset_pwd" class="btn btn-primary my-4">Reset Password</button>
            </div>

            <div class="row mt-3">
              <div class="col-6">
                <a href="../customer/index.php" class="text-white"><strong><u>Log In?</u></strong></a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

    <!-- Footer -->
    <?php
    require_once('partials/_footer.php');
    ?>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
  </div>
</body>

</html>