<?php
include 'partials/header.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and validate the new password
    $new_password = trim($_POST['new-password']);

    // Check if the password meets the minimum length requirement
    if (strlen($new_password) < 6) {
        $_SESSION['reset-error'] = "Password must be at least 6 characters long.";
    } else {
        // Hash the password using bcrypt
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Get the email from the session
        $email = $_SESSION['reset-email'];

        // Prepare the query to update the user's password
        $query = "UPDATE users SET password=? WHERE email=?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $email);

        if (mysqli_stmt_execute($stmt)) {
            // Clear the reset-related session variables
            unset($_SESSION['reset-code'], $_SESSION['reset-email']);

            // Set success message
            $_SESSION['signin'] = "Password reset successfully. You can now sign in.";
            header('location: signin.php');
            exit;
        } else {
            // If there was an issue with the query execution
            $_SESSION['reset-error'] = "Failed to reset password. Please try again.";
        }
    }
}
?>

<section class="form-section">
    <div class="container form-section-container">
        <h2>Reset Your Password</h2>
        <form action="" method="POST">
            <input type="password" name="new-password" placeholder="Enter new password" required>
            <button type="submit" name="submit" class="btn">Reset Password</button>
        </form>

        <?php if (isset($_SESSION['reset-error'])): ?>
            <p class="error"><?= $_SESSION['reset-error'];
                                unset($_SESSION['reset-error']); ?></p>
        <?php endif; ?>
    </div>
</section>