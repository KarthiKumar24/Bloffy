<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if category exists
    $check_query = "SELECT * FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($connection, $check_query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Set posts' category_id to NULL (or set to a default valid category)
        $update_query = "UPDATE posts SET category_id = NULL WHERE category_id = ?";
        $update_stmt = mysqli_prepare($connection, $update_query);
        mysqli_stmt_bind_param($update_stmt, "i", $id);
        mysqli_stmt_execute($update_stmt);

        // Delete category using prepared statement
        $delete_query = "DELETE FROM categories WHERE id = ? LIMIT 1";
        $delete_stmt = mysqli_prepare($connection, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        $delete_result = mysqli_stmt_execute($delete_stmt);

        if ($delete_result) {
            $_SESSION['delete-category-success'] = "Category deleted successfully";
        } else {
            $_SESSION['delete-category'] = "Failed to delete category";
        }
    } else {
        $_SESSION['delete-category'] = "Category not found";
    }
}

header('location:' . ROOT_URL . 'admin/manage-categories.php');
die();
