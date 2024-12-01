<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $avatar = $_FILES['avatar'];

    if (!$firstname || !$lastname || !$username || !$email) {
        $_SESSION['signup'] = "All fields are required.";
    } elseif (strlen($createpassword) < 8 || $createpassword !== $confirmpassword) {
        $_SESSION['signup'] = "Passwords must match and be at least 8 characters.";
    } elseif (!$avatar['name']) {
        $_SESSION['signup'] = "Please upload an avatar.";
    } else {
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email'";
        $user_check_result = mysqli_query($connection, $user_check_query);
        if (mysqli_num_rows($user_check_result) > 0) {
            $_SESSION['signup'] = "Username or Email already exists.";
        } else {
            $time = time();
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination_path = 'images/' . $avatar_name;
            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = strtolower(pathinfo($avatar_name, PATHINFO_EXTENSION));

            if (in_array($extension, $allowed_files) && $avatar['size'] < 1000000) {
                if (move_uploaded_file($avatar_tmp_name, $avatar_destination_path)) {
                    $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);
                    $insert_user_query = "INSERT INTO users SET 
                        firstname='$firstname', 
                        lastname='$lastname',
                        username='$username', 
                        email='$email',
                        password='$hashed_password',
                        avatar='$avatar_name',
                        is_admin=0";
                        
                    if (mysqli_query($connection, $insert_user_query)) {
                        $_SESSION['signup-success'] = "Registration successful. Please log in.";
                        header('Location: ' . ROOT_URL . 'signin.php');
                        die();
                    } else {
                        $_SESSION['signup'] = "Database error: " . mysqli_error($connection);
                    }
                } else {
                    $_SESSION['signup'] = "File upload failed.";
                }
            } else {
                $_SESSION['signup'] = "Invalid avatar file.";
            }
        }
    }

    if (isset($_SESSION['signup'])) {
        $_SESSION['signup-data'] = $_POST;
        header('Location: ' . ROOT_URL . 'signup.php');
        die();
    }
} else {
    header('Location: ' . ROOT_URL . 'signup.php');
    die();
}
