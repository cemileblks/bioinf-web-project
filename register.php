<?php
session_start();
require_once 'db/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$username || !$email || !$password) {
        $message = "❌ All fields are required!";
    } else {
        $check = $pdo->prepare("SELECT * FROM Users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->rowCount() > 0) {
            $message = "⚠️ Username or email already associated with an account. Please <a href='login_user.php'>log in</a> or try with another username.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed])) {
                $message = "✅ Registration successful! <a href='login_user.php'>Log in</a>";
            } else {
                $message = "❌ Something went wrong. Please try again.";
            }
        }
    }
}

echo <<<_HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | ProteinSwirl</title>
</head>
<body>
    <h2>Create a new account</h2>
_HTML;

if (!empty($message)) {
    echo "<p>$message</p>";
}

echo <<<_FORM
    <form method="post" action="register.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login_user.php">Log in</a></p>
</body>
</html>
_FORM;
