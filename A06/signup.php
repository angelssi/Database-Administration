<?php
include("connect.php");

$error = "";
$success = "";

if (isset($_POST['btnSignUp'])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $birthDate = $_POST['birthDate'];
  $phoneNumber = $_POST['phoneNumber'];

  $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
  $result = executeQuery($checkEmailQuery);

  if (mysqli_num_rows($result) > 0) {
    $error = "This email is already registered.";
  } else {
    $insertUserQuery = "INSERT INTO users (username, email, password, phoneNumber) 
                        VALUES ('$username', '$email', '$password', '$phoneNumber')";
    if (executeQuery($insertUserQuery)) {
      $userID = mysqli_insert_id($GLOBALS['conn']);

      $insertUserInfoQuery = "INSERT INTO userinfo (firstName, lastName, birthDate) 
                        VALUES ('$firstName', '$lastName', '$birthDate')";
if (executeQuery($insertUserInfoQuery)) {
    $success = "User registered successfully. Please log in.";
} else {
    $error = "An error occurred while saving user information.";
}
    } else {
      $error = "An error occurred while registering. Please try again.";
    }
  }
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up</title>
  <link rel="icon" href="images/symbol.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body style="background: linear-gradient(to right, #78FFDB, #93D5FC, #8ED2F8, #6BBFDF);">
  <div class="container">
    <?php if ($error) { ?>
      <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-6">
          <div class="alert alert-danger d-flex justify-content-center align-items-center text-center" role="alert">
            <div class="me-2">
              <?php echo $error; ?>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    <?php if ($success) { ?>
      <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-md-6">
          <div class="alert alert-success d-flex justify-content-center align-items-center text-center" role="alert">
            <div class="me-2">
              <?php echo $success; ?>
            </div>
            <div class="ms-2">
              <a href="login.php" class="btn text-white" style="background: #5DADEC;">Login</a>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>

    <div class="row justify-content-center my-5">
      <div class="col-12 col-sm-8 col-md-6">
        <div class="card p-4 text-center rounded-5 shadow">
          <img src="images/logo.png" class="logo mx-auto mb-4" style="width: 30%;" alt="logo-signup">
          <div class="h3 mb-2"><b>Sign Up</b></div>
          <form method="POST">
            <input class="my-3 form-control" placeholder="First Name" name="firstName" type="text" required>
            <input class="my-3 form-control" placeholder="Last Name" name="lastName" type="text" required>
            <input class="my-3 form-control" placeholder="Username" name="username" type="text" required>
            <input class="my-3 form-control" placeholder="Email" name="email" type="email" required>
            <input class="my-3 form-control" placeholder="Password" name="password" type="password" required>
            <input class="my-3 form-control" placeholder="Birth Date" name="birthDate" type="date" required>
            <input class="my-3 form-control" placeholder="09XXXXXXXXX" name="phoneNumber" type="text"  maxlength="11"  required>
            <button class="mt-2 btn text-white" style="background: #5DADEC;" name="btnSignUp">Sign Up</button>
          </form>
          <p class="mt-2">Already have an account? <a href="login.php" style="color: #5DADEC;">Login here</a></p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>
