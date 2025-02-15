<?php
include 'partials/header.php';

// Fetch current user's posts from the database
$current_user_id = $_SESSION['user-id'];
$is_admin = isset($_SESSION['user_is_admin']) && $_SESSION['user_is_admin'];

if ($is_admin) {
    // Admin can see all posts
    $query = "SELECT id, title, category_id, author_id FROM posts ORDER BY id DESC";
} else {
    // Author can only see their own posts
    $query = "SELECT id, title, category_id, author_id FROM posts WHERE author_id = $current_user_id ORDER BY id DESC";
}
$posts = mysqli_query($connection, $query);

?>

<section class="dashboard">
    <?php if (isset($_SESSION['add-post-success'])) : // shows if add post was successfull
    ?>
        <div class="alert-message success container">
            <p>
                <?= $_SESSION['add-post-success'];
                unset($_SESSION['add-post-success']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['edit-post-success'])) : // if edit post was successfull
    ?>
        <div class="alert-message success container">
            <p>
                <?= $_SESSION['edit-post-success'];
                unset($_SESSION['edit-post-success']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['edit-post'])) : // if edit post was NOT successfull
    ?>
        <div class="alert-message error container">
            <p>
                <?= $_SESSION['edit-post'];
                unset($_SESSION['edit-post']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['delete-post'])) : // if delete post was NOT successfull
    ?>
        <div class="alert-message error container">
            <p>
                <?= $_SESSION['delete-post'];
                unset($_SESSION['delete-post']);
                ?>
            </p>
        </div>
    <?php elseif (isset($_SESSION['delete-post-success'])) : // if delete post was NOT successfull
    ?>
        <div class="alert-message error container">
            <p>
                <?= $_SESSION['delete-post-success'];
                unset($_SESSION['delete-post-success']);
                ?>
            </p>
        </div>
    <?php endif ?>
    <button id="show-sidebar-btn" class="sidebar-toggle"><i class="uil uil-angle-right-b"></i></button>
    <button id="hide-sidebar-btn" class="sidebar-toggle"><i class="uil uil-angle-left-b"></i></button>
    <div class="container dashboard-container">
        <aside>
            <ul>
                <li>
                    <a href="add-post.php"><i class="uil uil-pen"></i>
                        <h5>Add Post</h5>
                    </a>
                </li>
                <li>
                    <a href="dashboard.php" class="active"><i class="uil uil-postcard"></i>
                        <h5>Manage Post</h5>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_is_admin'])) : ?>
                    <li>
                        <a href="add-user.php"><i class="uil uil-user-plus"></i>
                            <h5>Add User</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-user.php"><i class="uil uil-users-alt"></i>
                            <h5>Manage User</h5>
                        </a>
                    </li>
                    <li>
                        <a href="add-category.php"><i class="uil uil-edit"></i>
                            <h5>Add Category</h5>
                        </a>
                    </li>
                    <li>
                        <a href="manage-categories.php"><i class="uil uil-list-ul"></i>
                            <h5>Manage Categories</h5>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </aside>
        <main>
            <h2>Manage Post</h2>
            <?php if (mysqli_num_rows($posts) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
                            <!-- Get category title of each post from category table -->
                            <?php
                            $category_id = $post['category_id'];
                            $category_query = "SELECT title FROM categories WHERE id = $category_id";
                            $category_result = mysqli_query($connection, $category_query);
                            $category = mysqli_fetch_assoc($category_result);
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($post['title']) ?></td>
                                <td><?= htmlspecialchars($category['title']) ?></td>
                                <td>
                                    <a href="<?= ROOT_URL ?>admin/edit-post.php?id=<?= $post['id'] ?>" class="btn sm">Edit</a>
                                </td>
                                <td>
                                    <a href="<?= ROOT_URL ?>admin/delete-post.php?id=<?= $post['id'] ?>" class="btn sm danger">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="alert-message error">No posts found</div>
            <?php endif ?>
        </main>
    </div>
</section>

<?php
include '../partials/footer.php';
?>