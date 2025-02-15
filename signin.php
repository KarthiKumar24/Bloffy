<?php
require 'config/constants.php';

$username_email = $_SESSION['signin-data']['username_email'] ?? null;
$password = $_SESSION['signin-data']['password'] ?? null;

unset($_SESSION['signin-data']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloffy</title>
    <link rel="shortcut icon" href="<?= ROOT_URL ?>images/BloffyLogo.svg">
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
    <!-- ICONSCOUT CDN LINK -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body>
    <section class="form-section">
        <div class="container form-section-container">
            <h2>Sign In</h2>
            <?php if (isset($_SESSION['signup-success'])) : ?>
                <div class="alert-message success">
                    <p>
                        <?= $_SESSION['signup-success']; ?>
                        <?php unset($_SESSION['signup-success']); ?>
                    </p>
                </div>
            <?php elseif (isset($_SESSION['signin'])) : ?>
                <div class="alert-message error">
                    <p>
                        <?= $_SESSION['signin']; ?>
                        <?php unset($_SESSION['signin']); ?>
                    </p>
                </div>
            <?php endif ?>
            <form action="<?= ROOT_URL ?>signin-logic.php" method="POST">
                <input type="text" name="username-email" value="<?= htmlspecialchars($username_email) ?>" placeholder="Username or Email">
                <input type="password" name="password" value="<?= htmlspecialchars($password) ?>" placeholder="Password">
                <button type="submit" name="submit" class="btn">Sign In</button>
                <small><a href="<?= ROOT_URL ?>forgotpassword.php">Forgot Password?</a></small> <small>Don't have an account? <a href="<?= ROOT_URL ?>signup.php">Sign Up</a></small>
            </form>
        </div>
    </section>
</body>

</html>