<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $createpassword = $_POST['createpassword'];
    $confirmpassword = $_POST['confirmpassword'];
    $is_admin = isset($_POST['userrole']) ? (int)$_POST['userrole'] : null;
    $avatar = $_FILES['avatar'];

    // Validation checks
    if (!$firstname) {
        $_SESSION['add-user'] = "Please enter your Firstname.";
    } elseif (!$lastname) {
        $_SESSION['add-user'] = "Please enter your Lastname.";
    } elseif (!$username) {
        $_SESSION['add-user'] = "Please enter your Username.";
    } elseif (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['add-user'] = "Please enter a valid Email.";
    } elseif (strlen($createpassword) < 8 || $createpassword !== $confirmpassword) {
        $_SESSION['add-user'] = "Passwords must match and be at least 8 characters.";
    } elseif (!preg_match('/[A-Za-z]/', $createpassword) || !preg_match('/[0-9]/', $createpassword)) {
        $_SESSION['add-user'] = "Password must contain letters and numbers.";
    } elseif (!$avatar['name'] || $avatar['error'] !== 0) {
        $_SESSION['add-user'] = "Please upload a valid avatar.";
    } elseif (is_null($is_admin)) {
        $_SESSION['add-user'] = "Please select user role.";
    } else {
        // Check if username or email already exists
        $user_check_query = "SELECT * FROM users WHERE username=? OR email=?";
        $stmt = $connection->prepare($user_check_query);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $user_check_result = $stmt->get_result();

        if ($user_check_result->num_rows > 0) {
            $_SESSION['add-user'] = "Username or Email already exists.";
        } else {
            // Handle avatar upload
            $time = time();
            $avatar_name = $time . $avatar['name'];
            $avatar_tmp_name = $avatar['tmp_name'];
            $avatar_destination_path = '../images/' . $avatar_name;

            $allowed_files = ['png', 'jpg', 'jpeg'];
            $extension = strtolower(pathinfo($avatar_name, PATHINFO_EXTENSION));

            if (in_array($extension, $allowed_files) && $avatar['size'] < 1000000) {
                if (move_uploaded_file($avatar_tmp_name, $avatar_destination_path)) {
                    $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);
                    $insert_user_query = "INSERT INTO users (firstname, lastname, username, email, password, avatar, is_admin) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $connection->prepare($insert_user_query);
                    $stmt->bind_param("ssssssi", $firstname, $lastname, $username, $email, $hashed_password, $avatar_name, $is_admin);

                    if ($stmt->execute()) {
                        $_SESSION['add-user-success'] = "New User '$firstname $lastname' added successfully.";
                        header('location: ' . ROOT_URL . 'admin/manage-user.php');
                        die();
                    } else {
                        $_SESSION['add-user'] = "Database error: " . $stmt->error;
                    }
                } else {
                    $_SESSION['add-user'] = "Failed to upload avatar. Ensure it's less than 1MB.";
                }
            } else {
                $_SESSION['add-user'] = "File should be png, jpg, or jpeg.";
            }
        }
    }

    // Redirect back to the form if there are errors
    if (isset($_SESSION['add-user'])) {
        $_SESSION['add-user-data'] = $_POST;
        header('Location: ' . ROOT_URL . 'admin/add-user.php');
        exit;
    }
} else {
    header('Location: ' . ROOT_URL . 'admin/add-user.php');
    exit;
}
