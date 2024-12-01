<?php
require '../config/constants.php'; // Ensures ROOT_URL is defined
require '../config/database.php'; // Include database connection

if (isset($_POST['submit'])) {
    // Get updated form data
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Check for valid input
    if (!$title || !$description) {
        $_SESSION['edit-category'] = "Invalid form input.";
    } else {
        // Update category
        $query = "UPDATE categories SET title='$title', description='$description' WHERE id=$id LIMIT 1";
        $result = mysqli_query($connection, $query);

        if (mysqli_errno($connection)) {
            $_SESSION['edit-category'] = "Failed to update category.";
        } else {
            $_SESSION['edit-category-success'] = "Category updated successfully.";
        }
    }
} else {
    $_SESSION['edit-category'] = "Please fill out the form.";
}

// Redirect back to manage categories page
header('location:' . ROOT_URL . 'admin/manage-categories.php');
die();
