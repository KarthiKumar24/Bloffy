<?php
require 'config/database.php';

// Fetch avatar if user is logged in
if (isset($_SESSION['user-id'])) {
    $user_id = $_SESSION['user-id'];
    $avatar_query = "SELECT avatar FROM users WHERE id=$user_id";
    $avatar_result = mysqli_query($connection, $avatar_query);
    if (mysqli_num_rows($avatar_result) == 1) {
        $avatar = mysqli_fetch_assoc($avatar_result);
    }
}
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
    <!-- AOS LINKS 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script> -->

    <!-- MAGIC UI LINKS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/magic-ui/dist/magic-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/magic-ui/dist/magic-ui.js"></script>


</head>

<body>
    <!-- Nav-bar -->
    <nav>
        <div class="container nav-container">
            <a href="<?= ROOT_URL ?>" class="nav-logo">Bloffy</a>
            <ul class="nav-items">
                <li><a href="<?= ROOT_URL ?>blog.php">Blog</a></li>
                <li><a href="<?= ROOT_URL ?>about.php">About</a></li>
                <li><a href="<?= ROOT_URL ?>contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user-id'])) : ?>
                    <li class="nav-profile">
                        <div class="avatar">
                            <img src="<?= ROOT_URL . 'images/' . $avatar['avatar'] ?>" alt="User Avatar">
                        </div>
                        <ul>
                            <li><a href="<?= ROOT_URL ?>admin/index.php">Dashboard</a></li>
                            <li><a href="<?= ROOT_URL ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li><a href="<?= ROOT_URL ?>signin.php">Sign In</a></li>
                <?php endif ?>
            </ul>
            <button id="open-nav-btn"><i class="uil uil-bars"></i></button>
            <button id="close-nav-btn" style="display:none;"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>


</body>

</html>