<?php
include 'partials/header.php'; // Corrected header inclusion

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Fetch category from database
    $query = "SELECT * FROM categories WHERE id=$id";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) == 1) {
        $category = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['edit-category'] = "Category not found.";
        header('location:' . ROOT_URL . 'admin/manage-categories.php');
        die();
    }
} else {
    header('location:' . ROOT_URL . 'admin/manage-categories.php');
    die();
}
?>
<section class="form-section">
    <div class="container form-section-container">
        <h2>Edit Category</h2>
        <form action="<?= ROOT_URL ?>admin/edit-category-logic.php" method="POST">
            <!-- Hidden field to pass the category ID -->
            <input type="hidden" value="<?= $category['id'] ?>" name="id">
            <input type="text" value="<?= htmlspecialchars($category['title']) ?>" name="title" placeholder="Title">
            <textarea rows="4" name="description" placeholder="Description"><?= htmlspecialchars($category['description']) ?></textarea>
            <button type="submit" name="submit" class="btn">Update Category</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php'; // Corrected footer inclusion
?>