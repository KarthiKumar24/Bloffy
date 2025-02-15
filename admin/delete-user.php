<?php
require 'config/database.php';

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    if ($id) {
        // Fetch user from the database
        $stmt = mysqli_prepare($connection, "SELECT avatar FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            $avatar_path = "../images/" . $user['avatar'];

            // Delete avatar if it exists
            if (!empty($user['avatar']) && file_exists($avatar_path)) {
                unlink($avatar_path);
            }

            // Delete user's posts and their thumbnails
            $thumbnails_query = "SELECT thumbnail FROM posts WHERE author_id = ?";
            $thumb_stmt = mysqli_prepare($connection, $thumbnails_query);
            mysqli_stmt_bind_param($thumb_stmt, "i", $id);
            mysqli_stmt_execute($thumb_stmt);
            $thumbnails_result = mysqli_stmt_get_result($thumb_stmt);

            while ($thumbnails = mysqli_fetch_assoc($thumbnails_result)) {
                $thumbnail_path = "../images/" . $thumbnails['thumbnail'];
                if (!empty($thumbnails['thumbnail']) && file_exists($thumbnail_path)) {
                    unlink($thumbnail_path);
                }
            }

            // Delete user from database
            $delete_stmt = mysqli_prepare($connection, "DELETE FROM users WHERE id = ?");
            mysqli_stmt_bind_param($delete_stmt, "i", $id);
            mysqli_stmt_execute($delete_stmt);

            if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
                $_SESSION['delete-user-success'] = "User deleted successfully.";
            } else {
                $_SESSION['delete-user'] = "Failed to delete user.";
            }

            mysqli_stmt_close($stmt);
            mysqli_stmt_close($thumb_stmt);
            mysqli_stmt_close($delete_stmt);
        }
    }
}

header("Location: manage-user.php");
exit();
?>
