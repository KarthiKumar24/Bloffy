<?php

require 'config/database.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If using Composer

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate the email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['forgot-error'] = "Please enter a valid email address.";
    } else {
        // Check if the email exists in the database
        $query = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            // Generate a 4-digit reset code
            $reset_code = rand(1000, 9999);

            // Save the reset code in the database
            $update_query = "UPDATE users SET reset_code=? WHERE email=?";
            $update_stmt = mysqli_prepare($connection, $update_query);
            mysqli_stmt_bind_param($update_stmt, 'is', $reset_code, $email);

            if (mysqli_stmt_execute($update_stmt)) {
                // Create a PHPMailer instance
                $mail = new PHPMailer(true);
                // SMTP Debugging should be set to 0 in production
                $mail->SMTPDebug = 0;
                $mail->Debugoutput = 'html';

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Using Gmail's SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 's.k807807807807@gmail.com';  // Use your domain's email address
                    $mail->Password = 'sinvufxmgsjahgdd';  // Use app-specific password if using Gmail
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('s.k807807807807@gmail.com', 'Bloffy Admin');  // Use your own email
                    $mail->addAddress($email); // Add recipient's email

                    // Additional headers to avoid spam filtering
                    $mail->addReplyTo('s.k807807807807@gmail.com', 'Bloffy Admin');  // Add a reply-to address

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Password Reset Code';
                    $mail->Body    = '<html><body>';
                    $mail->Body   .= '<h3>Password Reset Request</h3>';
                    $mail->Body   .= '<p>Hi,</p>';
                    $mail->Body   .= '<p>You requested a password reset. Here is your reset code:</p>';
                    $mail->Body   .= '<h2 style="color: #28a745;">' . $reset_code . '</h2>';
                    $mail->Body   .= '<p>If you did not request this, please ignore this email.</p>';
                    $mail->Body   .= '</body></html>';

                    // Send email
                    if ($mail->send()) {
                        $_SESSION['reset-email'] = $email;
                        header('location: reset-code.php');
                        exit;
                    } else {
                        $_SESSION['forgot-error'] = "Failed to send the reset code. Please try again.";
                    }
                } catch (Exception $e) {
                    $_SESSION['forgot-error'] = "Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $_SESSION['forgot-error'] = "Failed to save the reset code. Try again.";
            }
        } else {
            $_SESSION['forgot-error'] = "Email not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="<?= ROOT_URL ?>css/style.css">
    <!-- ICONSCOUT CDN LINK -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

</head>

<body>
    <section class="form-section">
        <div class="container form-section-container">
            <h2>Forgot Password</h2>
            <?php if (isset($_SESSION['forgot-error'])): ?>
                <div class="alert-message error">
                    <p><?= $_SESSION['forgot-error'];
                        unset($_SESSION['forgot-error']); ?></p>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <input type="email" name="email" placeholder="Enter your registered email" required>
                <button type="submit" name="submit" class="btn">Send Reset Code</button>
            </form>
        </div>
    </section>
</body>

</html>