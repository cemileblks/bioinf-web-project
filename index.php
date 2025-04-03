<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protein Swirl 🌀</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
    <?php
    include './features/navbar.php';
    ?>

    <main>
        <section style="background: url('https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/assets/images/swirl-bg2.png') center/cover no-repeat; height: 90vh; display: flex; flex-direction: column; justify-content: center; align-items: center; color: white; text-shadow: 1px 1px 5px #000;">
            <h1>Welcome to Protein Swirl 🌀</h1>
            <p>Explore, analyze, and visualize protein sequences with ease.</p>
            <div style="margin-top: 20px;">
                <a href="https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/login_user.php" class="btn">🔐 Login</a>
                <a href="https://bioinfmsc8.bio.ed.ac.uk/~s2756532/web_project/search_form.php" class="btn">🚀 Try a Search</a>
            </div>
        </section>

    </main>
</body>

<?php
include './features/footer.php';
?>

</html>