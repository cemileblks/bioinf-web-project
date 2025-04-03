<?php
session_start();
require_once 'db/db_connection.php';

// Get field info from form using post method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Render message if one field is absent
    if (!$username || !$email || !$password) {
        $message = "❌ All fields are required!";
    } else {
        // Search db if a user was created with same creditentials before
        $check = $pdo->prepare("SELECT * FROM Users WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->rowCount() > 0) {
            $message = "⚠️ Username or email already exists. Try <a href='login_user.php'>logging in</a>.";
        } else {
            // convert the password to hased for better security
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Populate database with new user
            $stmt = $pdo->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");

            // Check if user was created successfully
            if ($stmt->execute([$username, $email, $hashed])) {
                $message = "✅ Registration successful! <a href='login_user.php'>Log in</a>";
            } else {
                $message = "❌ Something went wrong. Please try again.";
            }
        }
    }
}

// Render HTML (start output buffering)
ob_start();
echo "<h2 class='form-heading'>Create a New Account</h2>";

// Display message if it exists
if (!empty($message)) {
    echo "<p class='info-msg'>$message</p>";
}

echo <<< _FORM
<form method="post" action="register.php" class="auth-form">
    <div class="form-group">
        <label>Username:</label>
        <input type="text" name="username" class="input-field" required>
    </div>

    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" class="input-field" required>
    </div>

    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" class="input-field" required>
    </div>

    <button type="submit" class="btn-submit">Register</button>
</form>
<p class="sub-text-form">Already have an account? <a href="login_user.php">Log in</a></p>
_FORM;

// Get the contents of the output buffer and turn it off
$pageContent = ob_get_clean();
$pageTitle = "Register";
include './features/base_layout.php';
