<?php
session_start();
require_once 'db/db_connection.php';

// Check if login details were given and show message if not
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $message = "❌ Please enter both email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if creditentials are corrent and set session id and username
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php"); // redirect to index.php (homepage)
            exit;
        } else {
            $message = "❌ Invalid credentials.";
        }
    }
}

// Render HTML (start output buffering)
ob_start();
echo "<h2 class='form-heading'>🔐 Log in to your account</h2>";

if (!empty($message)) {
    echo "<p class='error-msg'>$message</p>";
}

echo <<< _FORM
<form method="post" action="login_user.php" class="auth-form">
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" class="input-field" required>
    </div>

    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" class="input-field" required>
    </div>

    <button type="submit" class="btn-submit">Log In</button>
</form>
<p class="sub-text-form">Don't have an account? <a href="register.php">Register here</a></p>
_FORM;

// Get the contents of the output buffer and turn it off
$pageContent = ob_get_clean();
$pageTitle = "Login";
include './features/base_layout.php';
