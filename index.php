<?php
include 'partials/header.php';

// Verify database connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch featured post from the database
$featured_query = "SELECT * FROM posts WHERE is_featured=1 LIMIT 1";
$featured_result = mysqli_query($connection, $featured_query);

if (!$featured_result) {
    die("Featured post query failed: " . mysqli_error($connection));
}

$featured = mysqli_num_rows($featured_result) > 0 ? mysqli_fetch_assoc($featured_result) : null;

// Fetch 9 latest posts
$query = "SELECT * FROM posts ORDER BY date_time DESC LIMIT 9";
$posts = mysqli_query($connection, $query);

if (!$posts) {
    die("Posts query failed: " . mysqli_error($connection));
}
?>

<!-- Show featured post if available -->
<?php if ($featured): ?>
    <section class="featured">
        <div class="container featured-container">
            <div class="post-thumbnail">
                <img src="./images/<?= htmlspecialchars($featured['thumbnail']) ?>" alt="Featured Post Thumbnail">
            </div>
            <div data-aos="fade" class="post-info">
                <?php
                // Fetch category for featured post
                $category_title = "Uncategorized";
                if (!empty($featured['category_id'])) {
                    $category_query = "SELECT title FROM categories WHERE id={$featured['category_id']}";
                    $category_result = mysqli_query($connection, $category_query);
                    if ($category_result && mysqli_num_rows($category_result) > 0) {
                        $category = mysqli_fetch_assoc($category_result);
                        $category_title = $category['title'];
                    }
                }
                ?>
                <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $featured['category_id'] ?>" class="category-button">
                    <?= htmlspecialchars($category_title) ?>
                </a>
                <h2 class="post-title">
                    <a href="<?= ROOT_URL ?>post.php?id=<?= $featured['id'] ?>">
                        <?= htmlspecialchars($featured['title']) ?>
                    </a>
                </h2>
                <p class="post-body">
                    <?= htmlspecialchars(substr($featured['body'], 0, 600)) ?>...
                </p>
                <div class="post-author">
                    <?php
                    // Fetch author for featured post
                    $author_name = "Unknown Author";
                    $author_avatar = "default-avatar.png";
                    if (!empty($featured['author_id'])) {
                        $author_query = "SELECT firstname, lastname, avatar FROM users WHERE id={$featured['author_id']}";
                        $author_result = mysqli_query($connection, $author_query);
                        if ($author_result && mysqli_num_rows($author_result) > 0) {
                            $author = mysqli_fetch_assoc($author_result);
                            $author_name = "{$author['firstname']} {$author['lastname']}";
                            $author_avatar = $author['avatar'];
                        }
                    }
                    ?>
                    <div class="post-author-avatar">
                        <img src="./images/<?= htmlspecialchars($author_avatar) ?>" width="100%" alt="Author Avatar">
                    </div>
                    <div class="post-author-info">
                        <h5><?= htmlspecialchars($author_name) ?></h5>
                        <small><?= date("M d, Y - H:i A", strtotime($featured['date_time'])) ?></small>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Post Container -->
<section class="posts <?= $featured ? '' : 'section-extra-margin' ?>">
    <div class="container posts-container">
        <?php while ($post = mysqli_fetch_assoc($posts)): ?>
            <article class="post">
                <div data-aos="fade-right" class="post-thumbnail">
                    <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" width="300px" height="300px" alt="Post Thumbnail">
                </div>
                <div data-aos="fade-left" class="post-info">
                    <?php
                    // Fetch category for each post
                    $category_title = "Uncategorized";
                    if (!empty($post['category_id'])) {
                        $category_query = "SELECT title FROM categories WHERE id={$post['category_id']}";
                        $category_result = mysqli_query($connection, $category_query);
                        if ($category_result && mysqli_num_rows($category_result) > 0) {
                            $category = mysqli_fetch_assoc($category_result);
                            $category_title = $category['title'];
                        }
                    }
                    ?>
                    <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category-button">
                        <?= htmlspecialchars($category_title) ?>
                    </a>
                    <h2 class="post-title">
                        <a href="<?= ROOT_URL ?>post.php?id=<?= $post['id'] ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a>
                    </h2>
                    <p class="post-body">
                        <?= htmlspecialchars(substr($post['body'], 0, 150)) ?>...
                    </p>
                </div>
                <div data-aos="zoom-out-up" class="post-author">
                    <?php
                    // Fetch author for each post
                    $author_name = "Unknown Author";
                    $author_avatar = "default-avatar.png";
                    if (!empty($post['author_id'])) {
                        $author_query = "SELECT firstname, lastname, avatar FROM users WHERE id={$post['author_id']}";
                        $author_result = mysqli_query($connection, $author_query);
                        if ($author_result && mysqli_num_rows($author_result) > 0) {
                            $author = mysqli_fetch_assoc($author_result);
                            $author_name = "{$author['firstname']} {$author['lastname']}";
                            $author_avatar = $author['avatar'];
                        }
                    }
                    ?>
                    <div class="post-author-avatar">
                        <img src="./images/<?= htmlspecialchars($author_avatar) ?>" width="40px" alt="Author Avatar">
                    </div>
                    <div class="post-author-info">
                        <h5><?= htmlspecialchars($author_name) ?></h5>
                        <small><?= date("M d, Y - H:i A", strtotime($post['date_time'])) ?></small>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</section>

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

<?php
include 'partials/footer.php';
?>
