<?php
include 'partials/header.php';

// fetch posts if id is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE category_id=$id ORDER BY date_time DESC";
    $posts = mysqli_query($connection, $query);
} else {
    header('location:' . ROOT_URL . 'blog.php');
    die();
}

?>

<header class="category-title">
    <h2>
        <?php
        // fetch category from category table using category_id of post
        $category_id = $id;
        $category_query = "SELECT*FROM categories WHERE id=$id";
        $category_result = mysqli_query($connection, $category_query);
        $category = mysqli_fetch_assoc($category_result);
        echo $category['title']
        ?>
    </h2>
</header>
<!-- Post Container -->
<?php if (mysqli_num_rows($posts) > 0): ?>
    <section class="posts">
        <div class="container posts-container">
            <?php while ($post = mysqli_fetch_assoc($posts)): ?>
                <article class="post">
                    <div class="post-thumbnail">
                        <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" width="300px" height="300px" alt="Post Thumbnail">
                    </div>
                    <div class="post-info">
                        <?php
                        // Fetch category for each post
                        $category_query = "SELECT title FROM categories WHERE id={$post['category_id']}";
                        $category_result = mysqli_query($connection, $category_query);
                        $category = mysqli_fetch_assoc($category_result);
                        ?>
                        <h2 class="post-title">
                            <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <p class="post-body">
                            <?= htmlspecialchars(substr($post['body'], 0, 150)) ?>...
                        </p>
                    </div>
                    <div class="post-author">
                        <?php
                        // Fetch author for each post
                        $author_query = "SELECT firstname, lastname, avatar FROM users WHERE id={$post['author_id']}";
                        $author_result = mysqli_query($connection, $author_query);
                        $author = mysqli_fetch_assoc($author_result);
                        ?>
                        <div class="post-author-avatar">
                            <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" width="40px" alt="Author Avatar">
                        </div>
                        <div class="post-author-info">
                            <h5><?= htmlspecialchars("{$author['firstname']} {$author['lastname']}") ?></h5>
                            <small><?= date("M d, Y - H:i A", strtotime($post['date_time'])) ?></small>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
<?php else: ?>
    <div class="alert-message error lg">
    <p>No posts found for this category</p>
    </div>

<?php endif ?>
<!--  -->

<!-- Category Buttons -->
<section class="category-buttons">
    <div class="container category-buttons-container">
        <?php
        $all_categories_query = "SELECT * FROM categories";
        $all_categories = mysqli_query($connection, $all_categories_query);
        ?>
        <?php while ($category = mysqli_fetch_assoc($all_categories)): ?>
            <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $category['id'] ?>" class="category-button"><?= htmlspecialchars($category['title']) ?></a>
        <?php endwhile; ?>
    </div>
</section>

<!-- Footer -->
<?php
include 'partials/footer.php'
?>