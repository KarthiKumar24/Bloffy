<?php
include 'partials/header.php';

// Fetch post from the database if ID is set
if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM posts WHERE id=$id";
    $result = mysqli_query($connection, $query);
    $post = mysqli_fetch_assoc($result);

    if (!$post) {
        // Redirect if the post is not found
        header('location: ' . ROOT_URL . 'blog.php');
        die();
    }
} else {
    // Redirect if no ID is provided
    header('location: ' . ROOT_URL . 'blog.php');
    die();
}
?>

<section class="singlepost">
    <div class="container singlepost-container">
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <div class="post-author">
            <?php
            // Fetch author from the users table using author_id
            $author_id = $post['author_id'];
            $author_query = "SELECT * FROM users WHERE id=$author_id";
            $author_result = mysqli_query($connection, $author_query);
            $author = mysqli_fetch_assoc($author_result);
            ?>
            <div class="post-author-avatar">
                <img src="./images/<?= htmlspecialchars($author['avatar']) ?>" width="50px" alt="Author Avatar">
            </div>
            <div>
                <h5><?= htmlspecialchars($author['firstname']) ?></h5>
                <small><?= date("M d, Y-H:i", strtotime($post['date_time'])) ?></small>
            </div>
        </div>
        <div class="singlepost-thumbnail">
            <img src="./images/<?= htmlspecialchars($post['thumbnail']) ?>" width="550px" alt="Post Thumbnail">
        </div>
        <p><?= nl2br(htmlspecialchars($post['body'])) ?></p>
    </div>
</section>

<?php
include 'partials/footer.php';
?>
