<?php
include 'partials/header.php';


// Fetch 9 latest posts
$query = "SELECT * FROM posts ORDER BY date_time DESC";
$posts = mysqli_query($connection, $query);
?>

<!-- 
<section class="search-bar">
    <form class="container search-bar-container" action="<?= ROOT_URL ?>" method="GET">
        <div>
            <i class="uil uil-search"></i>
            <input type="search" name="" placeholder="Search">
        </div>
        <button type="submit" class="btn">Go</button>
    </form>
</section> -->
<!-- Post Container -->
<section class="posts <?= $featured ? '' : 'section-extra-margin' ?>">
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
                    <a href="<?= ROOT_URL ?>category-posts.php?id=<?= $post['category_id'] ?>" class="category-button">
                        <?= htmlspecialchars($category['title']) ?>
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