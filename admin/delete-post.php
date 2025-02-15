<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if the post exists
    $query = "SELECT * FROM posts WHERE id=$id LIMIT 1";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $post = mysqli_fetch_assoc($result);
        $thumbnail_name = $post['thumbnail'];
        $thumbnail_path = '../images/' . $thumbnail_name;

        // Delete thumbnail file if it exists
        if (file_exists($thumbnail_path)) {
            unlink($thumbnail_path);
        }

        // Delete post from the database
        $delete_post_query = "DELETE FROM posts WHERE id=$id LIMIT 1";
        $delete_post_result = mysqli_query($connection, $delete_post_query);

        if ($delete_post_result) {
            $_SESSION['delete-post-success'] = "Post deleted successfully.";
        } else {
            $_SESSION['delete-post-error'] = "Failed to delete the post.";
        }
    } else {
        $_SESSION['delete-post-error'] = "Post not found.";
    }
} else {
    $_SESSION['delete-post-error'] = "Invalid post ID.";
}

// Redirect to admin dashboard
header('Location: ' . ROOT_URL . 'admin/');
exit();
