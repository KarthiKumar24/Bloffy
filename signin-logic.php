<?php
session_start();
require 'config/database.php';

if (isset($_POST['submit'])) {
    // get form data
    $username_email = filter_var($_POST['username-email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username_email) {
        $_SESSION['signin'] = "Username or Email required";
    } elseif (!$password) {
        $_SESSION['signin'] = "Password Required";
    } else {
        // fetch user from database using prepared statement
        $fetch_user_query = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = mysqli_prepare($connection, $fetch_user_query);
        mysqli_stmt_bind_param($stmt, 'ss', $username_email, $username_email);
        mysqli_stmt_execute($stmt);
        $fetch_user_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($fetch_user_result) == 1) {
            // Convert the record into assoc array
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];

            // Compare form pass with db pass
            if (password_verify($password, $db_password)) {
                // set session for access control
                session_regenerate_id(true);
                $_SESSION['user-id'] = $user_record['id'];
                if ($user_record['is_admin'] == 1) {
                    $_SESSION['user_is_admin'] = true;
                }
                // log user in
                header("location:" . ROOT_URL . "admin/");
                exit;
            } else {
                $_SESSION['signin'] = "Invalid credentials. Please check your input.";
            }
        } else {
            $_SESSION['signin'] = "User not found";
        }
    }
    // if any problem, redirect back to signin page with login data
    if (isset($_SESSION['signin'])) {
        $_SESSION['signin-data'] = $_POST;
        header('location:' . ROOT_URL . 'signin.php');
        exit;
    }
} else {
    header('location:' . ROOT_URL . 'signin.php');
    exit;
}
