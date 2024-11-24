<?php
include("connect.php"); 

$email = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';  

if (!$email) {
    header("Location: login.php");
    exit();
}

$joinQuery = "SELECT u.userProfilePic, u.username, u.email, ui.firstName, ui.lastName, ui.birthDate, u.phoneNumber
        FROM users u
        JOIN userinfo ui ON u.userInfoID = ui.userInfoID
        WHERE u.email = '$email'";

$result = mysqli_query($conn, $joinQuery); 

if ($result) {
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $userProfilePic = $user['userProfilePic'];
        $username = $user['username'];
        $firstName = $user['firstName'];
        $lastName = $user['lastName'];
        $birthDate = $user['birthDate'];
        $phoneNumber = $user['phoneNumber'];
        $email = $user['email'];
    } else {
        echo "User not found.";
        exit();
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profile</title>
  <link rel="icon" href="images/symbol.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<style>
        body {
            padding-top: 20px;
        }
        
        .btnMode {
            border: 0px;
            background-color: transparent;
            text-decoration: none;
            outline: none;
            width: 40px;
        }

        .navbar .container-fluid {
            flex-wrap: nowrap;
        }

        .btnMode:focus {
            outline: none;
        }

        .btnMode:hover {
            transform: scale(1.2);
        }

        .mode-icon {
            transition: transform 0.3s ease;
        }
        .container {
            margin-top: 80px;
        }

        .profile-card {
            width: 100%;
            background-color: rgba(93, 173, 236, 0.5);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
        }
        .profile-card img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        [data-bs-theme="light"] .profile-info {
            font-size: 16px;
            color: #333;
        }
        .profile-info h1 {
            font-weight: bold;
        }
        .profile-info p {
            margin: 0;
        }
    </style>

<body id="body" data-bs-theme="light">
<nav class="navbar navbar-expand-lg bg-body-tertiary shadow fixed-top" style="flex-wrap: nowrap;">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a href="index.php" class="d-flex align-items-center" style="text-decoration: none; padding: 0;">
                <img src="images/logo.png" class="logonav ms-3" style="width: 13vh; border: none; outline: none;"
                    alt="logonav">
            </a>
            <div class="buttons d-flex align-items-center">
                <button class="btnMode p-0 me-3" onclick="changeMode()" style="background: none; border: none;">
                    <img id="light-mode-icon" class="mode-icon" style="width: 4vh;" src="images/Sun.png"
                        alt="light-mode-icon">
                </button>
                <div class="dropdown">
                    <button class="btn text-white dropdown-toggle" style="background-color: #5DADEC;" type="button"
                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="profile.php">View Profile</a></li>
                        <li><a class="dropdown-item" href="login.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

<div class="container d-flex justify-content-center">
    <div class="profile-card">
      <img src="images/<?php echo $user['userProfilePic']; ?>" alt="Avatar" class="avatar mb-0">
        <div class="profile-info" data-bs-theme="light">
            <h1><?php echo $user['username']; ?></h1>
            <p>Birthday: <?php echo $user['birthDate']; ?></p>
            <p>Phone: <?php echo $user['phoneNumber']; ?></p>
            <p>Email: <?php echo $user['email']; ?></p>
        </div>
    </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script>

var colorMode = "light";

function changeMode() {
    var bodyElement = document.getElementById("body");
    var iconElement = document.getElementById("light-mode-icon");

    colorMode = colorMode === "dark" ? "light" : "dark";
    bodyElement.setAttribute("data-bs-theme", colorMode);

    if (colorMode === "dark") {
        iconElement.src = "images/Moon.png";
        iconElement.alt = "dark-mode-icon";
    } else {
        iconElement.src = "images/Sun.png";
        iconElement.alt = "light-mode-icon";
    }
}
</script>

</body>

</html>
