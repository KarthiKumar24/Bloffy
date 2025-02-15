<?php
include 'partials/header.php';

// Get back form data if invalid
$title = $_SESSION['add-category-data']['title'] ?? '';
$description = $_SESSION['add-category-data']['description'] ?? '';

unset($_SESSION['add-category-data']);
?>

<section class="form-section">
    <div class="container form-section-container">
        <h2>Add Category</h2>
        <?php if (isset($_SESSION['add-category'])) : ?>
            <div class="alert-message error">
                <p><?= $_SESSION['add-category']; unset($_SESSION['add-category']); ?></p>
            </div>
        <?php endif ?>
        
        <form action="<?= ROOT_URL ?>admin/add-category-logic.php" method="POST">
            <input type="text" value="<?= htmlspecialchars($title) ?>" name="title" placeholder="Title">
            <textarea rows="4" name="description" placeholder="Description"><?= htmlspecialchars($description) ?></textarea>
            <button type="submit" name="submit" class="btn">Add Category</button>
        </form>
    </div>
</section>

<?php include '../partials/footer.php'; ?>
