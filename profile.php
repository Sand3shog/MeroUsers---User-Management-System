<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $update_sql = "UPDATE users SET first_name=?, last_name=?, email=?, phone=? WHERE id=?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $user_id);
        
        if ($update_stmt->execute()) {
            $success = "Profile updated successfully!";
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
        } else {
            $error = "Error updating profile.";
        }
    }

    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_pass_sql = "UPDATE users SET password=? WHERE id=?";
                $update_pass_stmt = $conn->prepare($update_pass_sql);
                $update_pass_stmt->bind_param("si", $hashed_password, $user_id);
                
                if ($update_pass_stmt->execute()) {
                    $success = "Password changed successfully!";
                } else {
                    $error = "Error changing password.";
                }
            } else {
                $error = "New passwords do not match!";
            }
        } else {
            $error = "Current password is incorrect!";
        }
    }

    if (isset($_POST['delete'])) {
        $delete_sql = "DELETE FROM users WHERE id=?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);
        
        if ($delete_stmt->execute()) {
            session_destroy();
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Simple Website</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">MeroUsers</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    </ul>
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" id="profileDropdown" data-toggle="dropdown">
                            <img src="<?php echo getProfileImage($user); ?>" 
                                 alt="Profile" 
                                 class="profile-image-nav">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="update_image.php">Update Profile Picture</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="row mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="mb-4">Your Profile</h2>
                        
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>

                            <hr>
                            <h4>Change Password</h4>
                            <form action="" method="POST">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" >
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" >
                            </div>

                            <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>

                            <div class="mt-4">
                                <button type="submit" name="update" class="btn btn-primary">Update Profile</button>
                                <button type="submit" name="delete" class="btn btn-danger ml-2" 
                                        onclick="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
                                    Delete Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Profile Picture</h5>
                        <img src="<?php echo getProfileImage($user); ?>" 
                             alt="Profile Picture" 
                             class="img-fluid rounded-circle profile-preview mb-3">
                        <a href="update_image.php" class="btn btn-primary btn-block">Update Picture</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center p-3">
        <p class="mb-0">&copy; Sandesh Maharjan. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
</body>
</html>
