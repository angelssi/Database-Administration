<?php
include("connect.php");

if (!isset($_COOKIE['email'])) {
    header("Location: login.php");
  }

  $loggedInUser = $_COOKIE['email']; 

// For the Delete Function
  if (isset($_POST['deletePostID'])) {
      $deletePostID = $_POST['deletePostID'];
  
      $checkOwnerResult = mysqli_query($conn, "SELECT u.email FROM users u JOIN posts p ON p.userID = u.userID WHERE p.postID = $deletePostID");

      $post = mysqli_fetch_assoc($checkOwnerResult); 

      if ($post['email'] == $loggedInUser) {
        mysqli_query($conn, "DELETE FROM posts WHERE postID = $deletePostID");
          }
  }

// For the Update Function
if (isset($_POST['editPostID']) && isset($_POST['editPostContent'])) {
    $editPostID = $_POST['editPostID'];
    $editPostContent = $_POST['editPostContent'];

    $checkPostIDQuery = "SELECT p.postID FROM posts p JOIN users u ON p.userID = u.userID WHERE p.postID = $editPostID AND u.email = '$loggedInUser'";
    $checkPostIDResult = mysqli_query($conn, $checkPostIDQuery);

    if (mysqli_num_rows($checkPostIDResult) > 0) {
        $updateQuery = "UPDATE posts SET content = '$editPostContent' WHERE postID = $editPostID";

        if (mysqli_query($conn, $updateQuery)) {
            echo "<script>alert('Post updated successfully.'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Error updating post.');</script>";
        }
    } 
}

    $PostJoinQuery = "
    SELECT p.postID, p.userID, p.content, p.dateTime, p.privacy, p.attachment,
           u.username, u.userProfilePic, u.email,
           c.commentID, c.userID AS commentUserID, c.content AS commentContent, c.dateTime AS commentDateTime,
           uc.username AS commentUsername, uc.userProfilePic AS commentUserProfilePic
    FROM posts p 
    LEFT JOIN users u ON p.userID = u.userID
    LEFT JOIN userinfo ui ON u.userInfoID = ui.userInfoID
    LEFT JOIN comments c ON p.postID = c.postID
    LEFT JOIN users uc ON c.userID = uc.userID
    WHERE p.isDeleted = 'No'
    ORDER BY p.dateTime DESC;";
 

$PostJoinResult = executeQuery($PostJoinQuery);

$posts = [];
while ($row = mysqli_fetch_assoc($PostJoinResult)) {
    $postID = $row['postID'];

    if (!isset($posts[$postID])) {
        $email = $row['email'];
        $username = $row['username'];
        $userProfilePic = $row['userProfilePic'];
        $content = $row['content'];
        $dateTime = $row['dateTime'];
        $privacy = $row['privacy'];
        $attachment = $row['attachment'];

        $posts[$postID] = array(
            $postID,
            $email,
            $username,
            $userProfilePic,
            $content,
            $dateTime,
            $privacy,
            $attachment,
            []
        );
    }

    if (!empty($row['commentID'])) {
        $commentUsername = $row['commentUsername'];
        $commentUserID = $row['commentUserID'];
        $commentUserProfilePic = $row['commentUserProfilePic'];
        $commentContent = $row['commentContent'];
        $commentDateTime = $row['commentDateTime'];

        $posts[$postID][8][] = array(
            $commentUsername,
            $commentUserID,
            $commentUserProfilePic,
            $commentContent,
            $commentDateTime
        );
    }
}


mysqli_close($conn);
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

        .btn-options:hover {
            transform: scale(1.1);
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

    <div class="container">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <div class="d-flex justify-content-between align-items-center mb-0">
                <div class="profile-pic">
                    <img src="images/<?php echo $post[3]; ?>" alt="Avatar" class="userProfilePic mb-0">
                    <strong class="username"><?php echo $post[2]; ?></strong>
                    <p class=" dateTime small ms-2 text-muted"><?php echo $post[5]; ?></p>
                </div>
                
                <div class="d-flex align-items-center">
                    <span class="privacy badge badge-info" style="background: linear-gradient(135deg, #78FFDB, #93D5FC, #8ED2F8, #6BBFDF);">
                        <?php echo $post[6]; ?>
                    </span>

                    <!-- Always Show the Dropdown -->
                    <div class="dropdown">
                        <button type="button" class="btn-options pt-0 ps-3 pb-3" style="text-decoration: none; background: none; outline: none; border: none;" data-bs-toggle="dropdown" aria-expanded="false">
                            <h2>...</h2>
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <button type="button" class="edit dropdown-item" 
                                        onclick="openEditModal(<?php echo $post[0]; ?>, '<?php echo htmlspecialchars($post[4], ENT_QUOTES); ?>')"
                                        <?php echo ($post[1] != $loggedInUser) ? 'disabled' : ''; ?>>
                                    Edit
                                </button>      
                            </li>
                            <li>
                                <form method="POST">
                                    <input type="hidden" name="deletePostID" value="<?php echo $post[0]; ?>">
                                    <button type="submit" class="delete dropdown-item" 
                                            onclick="return confirm('Are you sure you want to delete this post?');"
                                            <?php echo ($post[1] != $loggedInUser) ? 'disabled' : ''; ?>>
                                        Delete
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Post Content -->
            <p><?php echo $post[4]; ?></p>

            <!-- If there's attachment -->
            <?php if ($post[7]): ?>
                <img src="images/<?php echo $post[7]; ?>" alt="Photo" class="photo">
            <?php endif; ?>

            <div class="reaction-comment-container">
                <div class="btn-group">
                    <button class="btn btn-sm text-white" style="background-color: #5DADEC;">Like</button>
                    <button class="btn btn-sm text-white" style="background-color: #5DADEC;">Comment</button>
                </div>
            </div>

            <div class="comments mt-2">
                <small>Comments:</small>
                <?php if (!empty($post[8])): ?>
                    <?php foreach ($post[8] as $comment): ?>
                        <div class="comment mt-2">
                            <img src="images/<?php echo $comment[2]; ?>" alt="Avatar" class="commentUserProfilePic">
                            <div class="content-dateTime">
                                <strong><?php echo $comment[0]; ?></strong>: <?php echo $comment[3]; ?>
                                <p class="small ms-2 text-muted"><?php echo $comment[4]; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="small text-muted">No comments yet.</p>
                <?php endif; ?>
            </div>

            <div class="write-comment mt-2">
                <input type="text" class="form-control form-control-sm" placeholder="Write a comment...">
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editPostForm" method="POST">
          <div class="mb-3">
            <label for="editPostContent" class="form-label">Post Content</label>
            <textarea class="form-control" id="editPostContent" name="editPostContent" rows="3"></textarea>
          </div>
          <input type="hidden" id="postId" name="editPostID">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="saveChanges" form="editPostForm">Save changes</button>
      </div>
    </div>
  </div>
</div>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


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

// Ensure the correct modal ID is being targeted
function openEditModal(postId, postContent) {
    console.log('Opening modal for postId:', postId); // Debugging line
    console.log('Post content:', postContent); // Debugging line

    // Set the values in the modal
    document.getElementById('postId').value = postId;
    document.getElementById('editPostContent').value = postContent;

    // Show the modal
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}



</script>
</body>
</html>
