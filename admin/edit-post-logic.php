<?php
require 'config/database.php';

if (isset($_POST['submit'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_id = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $previous_thumbnail_name = $_POST['previous_thumbnail_name'];
    $thumbnail = $_FILES['thumbnail'];

    // Validate form data
    if (!$title || !$body || !$category_id) {
        $_SESSION['edit-post'] = "All fields are required.";
        header('location: ' . ROOT_URL . "admin/edit-post.php?id=$id");
        die();
    }

    // Handle thumbnail if uploaded
    if ($thumbnail['name']) {
        $time = time();
        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_destination_path = '../images/' . $thumbnail_name;

        // Ensure file is an image
        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = strtolower(pathinfo($thumbnail_name, PATHINFO_EXTENSION));
        if (in_array($extension, $allowed_files)) {
            // Ensure image is not too large
            if ($thumbnail['size'] < 2_000_000) {
                // Upload new thumbnail
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_destination_path);

                // Delete old thumbnail if it exists
                if ($previous_thumbnail_name) {
                    $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;
                    if (file_exists($previous_thumbnail_path)) {
                        unlink($previous_thumbnail_path);
                    }
                }
            } else {
                $_SESSION['edit-post'] = "Thumbnail size must be less than 2MB.";
                header('location: ' . ROOT_URL . "admin/edit-post.php?id=$id");
                die();
            }
        } else {
            $_SESSION['edit-post'] = "Thumbnail must be a PNG, JPG, or JPEG file.";
            header('location: ' . ROOT_URL . "admin/edit-post.php?id=$id");
            die();
        }
    } else {
        $thumbnail_name = $previous_thumbnail_name;
    }

    // Update featured post status if checked
    if ($is_featured) {
        $zero_featured_query = "UPDATE posts SET is_featured = 0";
        mysqli_query($connection, $zero_featured_query);
    }

    // Update post in database
    $query = "UPDATE posts SET title = '$title', body = '$body', thumbnail = '$thumbnail_name', category_id = $category_id, is_featured = $is_featured WHERE id = $id";
    $result = mysqli_query($connection, $query);

    if (!mysqli_errno($connection)) {
        $_SESSION['edit-post-success'] = "Post updated successfully.";
        header('location: ' . ROOT_URL . 'admin/');
        die();
    }
}

header('location: ' . ROOT_URL . 'admin/edit-post.php?id=' . $id);
die();
