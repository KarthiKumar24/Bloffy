<?php
include 'partials/header.php';

require 'vendor/autoload.php'; // Include PHPMailer (if using Composer)

// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    // Sanitize and validate form inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Create PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 's.k807807807807@gmail.com';  // Your email address
            $mail->Password = 'sinvufxmgsjahgdd';  // Gmail App Password or Email Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($email);
            $mail->addAddress("s.k807807807807@gmail.com", 'Karthi');  // Your recipient's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Message from Contact Form';
            $mail->Body    = "
            <h1>Hello Cheif<h1>
                <h3>Here is Client Contact Details</h3>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>

                <p>
            ";

            // Send email
            if ($mail->send()) {
                $success = "Your message has been sent successfully!";
            } else {
                $error = "Failed to send the message. Please try again later.";
            }
        } catch (Exception $e) {
            $error = "Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
<section class="form-section">
    <div class="container form-section-container">
        <h2>Contact Us</h2>

        <?php if (isset($error)): ?>
            <div class="alert-message error">
                <p><?= $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert-message success">
                <p><?= $success; ?></p>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <input type="text" name="name" placeholder="Enter your name" required>
            <input type="email" name="email" placeholder="Enter your email" required>
            <textarea name="message" placeholder="Your message" required></textarea>
            <button type="submit" name="submit" class="btn">Send Message</button>
        </form>
    </div>
</section>

</body>

</html>



<?php include 'partials/footer.php'; ?>