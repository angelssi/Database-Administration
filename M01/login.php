<?php
include("connect.php");

setcookie('firstName', null, time() + (86400 * 7), "/");
setcookie('lastName', null, time() + (86400 * 7), "/");
setcookie('birthDate', null, time() + (86400 * 7), "/");
setcookie('phoneNumber', null, time() + (86400 * 7), "/");
setcookie('email', null, time() + (86400 * 7), "/");

$error = "";
$showError = false;

if (isset($_POST['btnLogin'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  
  $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = executeQuery($query);

  if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    setcookie('email', $user['email'], time() + (86400 * 7), "/");
    header("Location: index.php");
  } else {
    $showError = true;
    $error = "User not found. Would you like to sign up?";
  }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link rel="icon" href="images/symbol.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="background: linear-gradient(to right, #78FFDB, #93D5FC, #8ED2F8, #6BBFDF);">
<div class="container">
  <?php if ($showError) { ?>
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="alert alert-danger d-flex justify-content-center align-items-center text-center" role="alert">
          <div class="me-2">
            <?php echo $error; ?>
          </div>
          <div class="ms-2">
            <a href="signup.php" class="btn text-white" style="background: #5DADEC;">Sign Up</a>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>

  <!-- Login Form -->
  <div class="row justify-content-center my-5">
    <div class="col-12 col-sm-8 col-md-6">
      <div class="card p-4 text-center rounded-5 shadow">
        <img src="images/logo.png" class="logo mx-auto mb-4" style="max-width: 30%;" alt="logo-login">
        <div class="h3 mb-2"><b>Welcome!</div></b>
        <form method="POST">
          <input class="my-3 form-control" placeholder="Email" name="email" type="email" required>
          <input class="my-3 form-control" placeholder="Password" name="password" type="password" required>
          <button class="my-5 btn text-white" style="background: #5DADEC;" name="btnLogin">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>

