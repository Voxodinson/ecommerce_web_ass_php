<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="text-center">
        <h1 class="display-4">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p class="lead">You are successfully registered.</p>
        <div class="d-flex align-items-center gap-2 justify-content-center">
            <a href="logout.php" class="btn btn-danger ">Logout</a><br>
            <a href="index.php" class="btn btn-primary">Go to Home Page</a>
        </div>
    </div>

    <!-- Bootstrap JS (Optional, for interactive elements like modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
