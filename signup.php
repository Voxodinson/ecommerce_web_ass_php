<?php
session_start();
include_once('services/config.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            $stmt = $con->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $error = "Email already exists.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $insert_stmt = $con->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                $insert_stmt->bindParam(":username", $username);
                $insert_stmt->bindParam(":email", $email);
                $insert_stmt->bindParam(":password", $hashed_password);
                
                if ($insert_stmt->execute()) {
                    $_SESSION['user_id'] = $con->lastInsertId();
                    $_SESSION['username'] = $username;
                    header("Location: welcome.php");
                    exit();
                } else {
                    $error = "Failed to register. Please try again.";
                }
            }
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
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="card p-4 shadow-lg" style="width: 400px;">
    <h3 class="text-center">Create an Account</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
    </form>

    <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
