<?php
include 'partials/header.php';

// Fetch categories from the database
$category_query = "SELECT * FROM categories";
$categories = mysqli_query($connection, $category_query);

// Fetch post data from database if `id` is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);

    // Check if the post exists
    if (mysqli_num_rows($result) > 0) {
        $post = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['edit-post'] = "Post not found.";
        header('location: ' . ROOT_URL . 'admin/');
        die();
    }
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}
?>

<section class="form-section">
    <div class="container form-section-container">
        <h2>Edit Post</h2>
        <?php if (isset($_SESSION['edit-post'])): ?>
            <div class="alert-message error">
                <?= $_SESSION['edit-post'];
                unset($_SESSION['edit-post']); ?>
            </div>
        <?php endif; ?>
        <form action="<?= ROOT_URL ?>admin/edit-post-logic.php" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="id" value="<?= $post['id'] ?>">
            <input type="hidden" name="previous_thumbnail_name" value="<?= $post['thumbnail'] ?>">
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" placeholder="Title">
            <select name="category">
                <?php while ($category = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $category['id'] ?>" <?= $category['id'] == $post['category_id'] ? 'selected' : '' ?>>
                        <?= $category['title'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <textarea rows="10" name="body" placeholder="Body"><?= htmlspecialchars($post['body']) ?></textarea>
            <div class="form-control inline">
                <input type="checkbox" id="is-featured" name="is_featured" value="1" <?= $post['is_featured'] ? 'checked' : '' ?>>
                <label for="is-featured">Featured</label>
            </div>
            <div class="form-control">
                <label for="thumbnail">Change Thumbnail</label>
                <input type="file" name="thumbnail" id="thumbnail">
            </div>
            <button type="submit" name="submit" class="btn">Update Post</button>
        </form>
    </div>
</section>

<?php
include '../partials/footer.php';
?>