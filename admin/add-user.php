<?php
include 'partials/header.php';

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get back form data if there was an error
$firstname = $_SESSION['add-user-data']['firstname'] ?? null;
$lastname = $_SESSION['add-user-data']['lastname'] ?? null;
$username = $_SESSION['add-user-data']['username'] ?? null;
$email = $_SESSION['add-user-data']['email'] ?? null;
$createpassword = $_SESSION['add-user-data']['createpassword'] ?? null;
$confirmpassword = $_SESSION['add-user-data']['confirmpassword'] ?? null;

// Delete session data to avoid retaining old input
unset($_SESSION['add-user-data']);
?>

<section class="form-section">
    <div class="container form-section-container">
        <h2>Add User</h2>
        <?php if (isset($_SESSION['add-user'])) : ?>
            <div class="alert-message error">
                <p>
                    <?= $_SESSION['add-user']; unset($_SESSION['add-user']); ?>
                </p>
            </div>
        <?php endif ?>
        <form action="<?= ROOT_URL ?>admin/add-user-logic.php" enctype="multipart/form-data" method="POST">
            <input type="text" name="firstname" value="<?= htmlspecialchars($firstname) ?>" placeholder="First Name">
            <input type="text" name="lastname" value="<?= htmlspecialchars($lastname) ?>" placeholder="Last Name">
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="Username">
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Email">
            <input type="password" name="createpassword" placeholder="Create Password">
            <input type="password" name="confirmpassword" placeholder="Confirm Password">
            <select name="userrole">
                <option value="0">Author</option>
                <option value="1">Admin</option>
            </select>
            <div class="form-control">
                <label for="avatar" class="avatar"></label>
                <input type="file" name="avatar" id="avatar">
            </div>
            <button type="submit" name="submit" class="btn">Add User</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>
