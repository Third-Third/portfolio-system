<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../config/db.php';

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ✅ IMPORTANT: hash input password to match DB
    $hashedPassword = md5($password);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM admin WHERE username=? AND password=?"
    );
    mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="container min-vh-100 d-flex align-items-center">
    <div class="row w-100 align-items-center">

        <!-- LOGIN FORM -->
        <div class="col-md-5">

            <img src="../assets/images/logo.png" height="40" class="mb-4">

            <h2 class="fw-bold">Admin Login</h2>
            <p class="text-muted">Please login to manage portfolio projects.</p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" name="login" class="btn btn-primary w-100">
                    Login
                </button>

            </form>

        </div>

        <!-- IMAGE -->
        <div class="col-md-7 d-none d-md-block text-center">
            <img src="../assets/images/login.png" class="img-fluid">
        </div>

    </div>
</div>

</body>
</html>