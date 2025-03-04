<?php
session_start();
include_once('services/config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";
$success = "";
$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT username, email, profile_image FROM users WHERE id = :id");
$stmt->bindParam(":id", $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['password'];
    $target_dir = "images/user/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (!empty($_FILES['profile_image']['name'])) {
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
        } else {
            $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
        }
    } else {
        $target_file = $user['profile_image'] ?: 'default.png';
    }

    if (empty($username) || empty($email)) {
        $error = "Username and Email are required.";
    } elseif (!empty($password) && $password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            $stmt = $con->prepare("UPDATE users SET username = :username, email = :email, profile_image = :profile_image WHERE id = :id");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":profile_image", $target_file);
            $stmt->bindParam(":id", $user_id);
            $stmt->execute();
            
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $con->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->bindParam(":password", $hashed_password);
                $stmt->bindParam(":id", $user_id);
                $stmt->execute();
            }
            
            $_SESSION['username'] = $username;
            $_SESSION['success_message'] = "Profile updated successfully.";  
            header("Location: edit_profile_seccess.php");

            exit();
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 500px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
        .edit-icon {
            cursor: pointer;
            color: #007bff;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="profile-container">
    <div class="text-center mb-3">
        <img 
            src="<?php echo htmlspecialchars($user['profile_image'] ?: 'images/user_image.jpg'); ?>" 
            alt="Profile Image" 
            class="profile-image">
    </div>
    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
    <p class="text-muted">@<?php echo htmlspecialchars($user['username']); ?> <span class="edit-icon">✎</span></p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success text-center"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="text-start">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Image</label>
            <input type="file" name="profile_image" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">New Password (Leave blank to keep current password)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="d-flex gap-3 margin-t-3">
            <a 
                href="javascript:history.back()" 
                class="btn btn-danger">Back</a>
            <button type="submit" name="update" class="btn btn-primary w-100">Update Profile</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
