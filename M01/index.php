<?php
include('connect.php');

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
  }

$joinQuery = "
SELECT p.postID, p.userID, p.content, p.dateTime, p.privacy, p.attachment,
       u.username, u.userProfilePic,
       c.commentID, c.userID AS commentUserID, c.content AS commentContent, c.dateTime AS commentDateTime,
       uc.username AS commentUsername, uc.userProfilePic AS commentUserProfilePic
FROM posts p 
LEFT JOIN users u ON p.userID = u.userID
LEFT JOIN comments c ON p.postID = c.postID
LEFT JOIN users uc ON c.userID = uc.userID
WHERE p.isDeleted = 'No'
ORDER BY p.dateTime DESC;";

$result = $conn->query($joinQuery);

$posts = [];
while ($row = $result->fetch_assoc()) {
    $postID = $row['postID'];

    if (!isset($posts[$postID])) {
        $posts[$postID] = [
            'username' => $row['username'],
            'userProfilePic' => $row['userProfilePic'], 
            'content' => $row['content'],
            'dateTime' => $row['dateTime'],
            'privacy' => $row['privacy'],
            'attachment' => $row['attachment'],
            'comments' => [], 
        ];
    }

    if (!empty($row['commentID'])) {
        $posts[$postID]['comments'][] = [
            'username' => $row['commentUsername'],
            'userID' => $row['commentUserID'],
            'userProfilePic' => $row['commentUserProfilePic'],
            'content' => $row['commentContent'],
            'dateTime' => $row['commentDateTime'],
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flock</title>
    <link rel="icon" href="images/symbol.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>

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
            width: 600px; 
            margin: 80px auto 0; 
        }
        .post {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-top: 5px;
            width: 100%; 
        }
        .photo {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .reaction-comment-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .btn-group {
            flex-grow: 1;
        }
        .btn {
            flex: 1; 
            margin-right: 5px; 
        }

        .profile-pic {
            display: flex;
            align-items: flex-start;
            margin-bottom: 4px;
        }
        .profile-pic img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }

        .comment {
            display: flex;
            align-items: flex-start;
            margin-bottom: 4px;
        }
        .comment img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
    </style>
</head>
<body id="body" data-bs-theme="light">
    <nav class="navbar navbar-expand-lg bg-body-tertiary d-flex justify-content-between shadow fixed-top mb-0 p-2">
    <a href="index.php">
    <img src="images/logo.png" class="logonav ms-3" style="max-width: 30%;" alt="logonav">
    </a>
        <div class="container-fluid d-flex justify-content-end align-items-center">
            <button class="btnMode p-0 m-0" onclick="changeMode()">
                <img id="light-mode-icon" class="mode-icon" style="max-width: 90%;" src="images/Sun.png" alt="light-mode-icon">
            </button>
            <div class="dropdown ms-3">
                <button class="btn text-white dropdown-toggle "  style="background-color: #5DADEC;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    Menu
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="profile.php">View Profile</a></li>
                    <li><a class="dropdown-item" href="login.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-1qnHgCxhFHGglXhfYHfijxVdEqY7kn0WyhRHtclE5ugopFh1rzWbbL5YbcYVwXbx" crossorigin="anonymous"></script>
</body>


    <div class="container">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <div class="profile-pic">
                        <img src="images/<?php echo $post['userProfilePic']; ?>" alt="Avatar" class="avatar mb-0">
                        <strong class="username"><?php echo $post['username']; ?></strong>
                        <p class="small ms-2 text-muted"><?php echo $post['dateTime']; ?></p>
                    </div>
                    <div>
                        <span class="badge badge-info" style="background: linear-gradient(135deg, #78FFDB, #93D5FC, #8ED2F8, #6BBFDF);"><?php echo $post['privacy']; ?></span>
                    </div>
                </div>
                
                <p><?php echo $post['content']; ?></p>
                
                <?php if ($post['attachment']): ?>
                    <img src="images/<?php echo $post['attachment']; ?>" alt="Photo" class="photo">
                <?php endif; ?>

                <div class="reaction-comment-container">
                    <div class="btn-group">
                        <button class="btn btn-sm text-white" style="background-color: #5DADEC;">Like</button>
                        <button class="btn btn-sm text-white" style="background-color: #5DADEC;">Comment</button>
                    </div>
                </div>

                <div class="comments mt-2">
                    <small>Comments:</small>
                    <?php if (!empty($post['comments'])): ?>
                        <?php foreach ($post['comments'] as $comment): ?>
                            <div class="comment mt-2">
                                <img src="images/<?php echo $comment['userProfilePic']; ?>" alt="Avatar" class="avatar">
                                <div>
                                    <strong><?php echo $comment['username']; ?></strong>: <?php echo $comment['content']; ?>
                                    <p class="small ms-2 text-muted"><?php echo $comment['dateTime']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="small text-muted">No comments yet.</p>
                    <?php endif; ?>
                </div>

                 <!-- Not yet working :))  -->
                <div class="write-comment mt-2">
                    <input type="text" class="form-control form-control-sm" placeholder="Write a comment...">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

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
