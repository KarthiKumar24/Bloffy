<?php
include 'partials/header.php';

// Check if the reset email exists in the session
if (isset($_SESSION['reset-email'])) {
    $email = $_SESSION['reset-email'];  // Get the email from the session

    if (isset($_POST['submit'])) {
        // Sanitize and validate the entered code
        $entered_code = trim($_POST['reset-code']);

        // Query to check if the entered reset code matches the one in the database
        $query = "SELECT reset_code FROM users WHERE email=?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stored_code = trim($row['reset_code']);  // Fetch the reset code from the database

            // Debugging output: Log the entered and stored code to verify they match
            echo "Entered code: " . htmlspecialchars($entered_code) . "<br>";
            echo "Stored code: " . htmlspecialchars($stored_code) . "<br>";

            // Compare the entered and stored code (ensure both are strings and no whitespace)
            if ((string)$entered_code === (string)$stored_code) {
                // Redirect to reset-password.php page if the codes match
                header('location: reset-password.php');
                exit;
            } else {
                $_SESSION['reset-error'] = "Invalid reset code. Please try again.";
            }
        } else {
            $_SESSION['reset-error'] = "No matching email found. Please request a new reset code.";
        }
    }
} else {
    $_SESSION['reset-error'] = "No reset code found. Please request a new reset code.";
    header('location: forgot-password.php');
    exit;
}
?>

<section class="form-section">
    <div class="container form-section-container">
        <h2>Verify Reset Code</h2>
        <form action="" method="POST">
            <input type="text" name="reset-code" placeholder="Enter 4-digit reset code" required>
            <button type="submit" name="submit" class="btn">Verify Code</button>
        </form>

        <?php if (isset($_SESSION['reset-error'])): ?>
            <p class="error"><?= $_SESSION['reset-error']; unset($_SESSION['reset-error']); ?></p>
        <?php endif; ?>
    </div>
</section>
