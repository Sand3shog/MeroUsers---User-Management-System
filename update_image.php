<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

function getProfileImage($user) {
    if (isset($user['profile_image']) && !empty($user['profile_image']) && file_exists($user['profile_image'])) {
        return htmlspecialchars($user['profile_image']);
    }
    return 'uploads/default-avatar.png';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_image'])) {
    if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "profile_" . $user_id . "_" . uniqid() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        $uploadOk = 1;

        $check = @getimagesize($_FILES["profile_image"]["tmp_name"]);
        if ($check === false) {
            $message = "<div class='alert alert-danger text-center'>File is not an image.</div>";
            $uploadOk = 0;
        }

        if ($_FILES["profile_image"]["size"] > 5000000) {
            $message = "<div class='alert alert-danger text-center'>File is too large. Maximum size is 5MB.</div>";
            $uploadOk = 0;
        }

        if (!in_array($file_extension, ["jpg", "jpeg", "png", "gif"])) {
            $message = "<div class='alert alert-danger text-center'>Only JPG, JPEG, PNG & GIF files are allowed.</div>";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (!empty($user['profile_image']) && file_exists($user['profile_image']) && $user['profile_image'] != 'uploads/default-avatar.png') {
                @unlink($user['profile_image']);
            }

            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $update_sql = "UPDATE users SET profile_image = ? WHERE id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $target_file, $user_id);
                
                if ($update_stmt->execute()) {
                    $message = "<div class='alert alert-success text-center'>Profile picture updated successfully!</div>";
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                } else {
                    $message = "<div class='alert alert-danger text-center'>Error updating database.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger text-center'>Error uploading file.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile Picture</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">HamroUsers</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Back to Profile</a></li>
                    </ul>
                    <img src="<?php echo getProfileImage($user); ?>" alt="Profile" class="profile-image-nav rounded-circle">
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-4">Update Profile Picture</h2>
                        <?php echo $message; ?>
                        <img src="<?php echo getProfileImage($user); ?>" alt="Current Profile Picture" class="profile-preview mb-3 rounded-circle" width="150">
                        <img id="imagePreview" alt="Image Preview" class="mb-3 rounded-circle d-none" width="150">

                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="profile_image" class="d-block">Choose New Profile Picture</label>
                                <input type="file" class="form-control-file mx-auto" id="profile_image" name="profile_image" accept="image/*" onchange="previewImage(this)">
                                <small class="form-text text-muted">Max size: 2MB | Formats: JPG, JPEG, PNG, GIF</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Update Picture</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center p-3 mt-4">
        <p class="mb-0">&copy; 2025 Simple Website. All rights reserved.</p>
    </footer>

    <script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.classList.remove('d-none');
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
