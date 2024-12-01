<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    $author_id = $_SESSION['user-id'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $thumbnail = $_FILES['thumbnail'];

    // Validate form data
    if (!$title) {
        $_SESSION['add-post'] = "Enter post title";
    } elseif (!$body) {
        $_SESSION['add-post'] = "Enter post body";
    } elseif (!$category_id) {
        $_SESSION['add-post'] = "Select a category";
    } elseif (empty($thumbnail['name'])) {
        $_SESSION['add-post'] = "Add a thumbnail image";
    }

    // Redirect back if validation fails
    if (isset($_SESSION['add-post'])) {
        $_SESSION['add-post-data'] = $_POST;
        header('location:' . ROOT_URL . 'admin/add-post.php');
        die();
    }

    // Process thumbnail
    $time = time(); // Rename the image with the current timestamp
    $thumbnail_name = $time . '-' . $thumbnail['name'];
    $thumbnail_tmp_name = $thumbnail['tmp_name'];
    $thumbnail_destination_path = '../images/' . $thumbnail_name;

    // Validate thumbnail file
    $allowed_files = ['png', 'jpg', 'jpeg'];
    $extension = pathinfo($thumbnail_name, PATHINFO_EXTENSION);

    if (!in_array($extension, $allowed_files)) {
        $_SESSION['add-post'] = "Thumbnail must be a png, jpg, or jpeg file";
    } elseif ($thumbnail['size'] > 2_000_000) {
        $_SESSION['add-post'] = "Thumbnail size must be less than 2MB";
    } else {
        // Upload thumbnail
        move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);
    }

    // Redirect back if there was an issue with the thumbnail
    if (isset($_SESSION['add-post'])) {
        $_SESSION['add-post-data'] = $_POST;
        header('location:' . ROOT_URL . 'admin/add-post.php');
        die();
    }

    // Handle featured post logic
    if ($is_featured == 1) {
        $zero_all_is_featured_query = "UPDATE posts SET is_featured = 0";
        mysqli_query($connection, $zero_all_is_featured_query);
    }

    // Insert post into the database
    $query = "INSERT INTO posts (title, body, thumbnail, category_id, author_id, is_featured) 
              VALUES ('$title', '$body', '$thumbnail_name', $category_id, $author_id, $is_featured)";
    $result = mysqli_query($connection, $query);

    if (!mysqli_errno($connection)) {
        $_SESSION['add-post-success'] = "New post added  successfully";
        header('location:' . ROOT_URL . 'admin/');
        die();
    }
}

// Redirect to the form by default
header('location:' . ROOT_URL . 'admin/add-post.php');
die();