<?php
include 'partials/header.php';

// Check database connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all users from the database
$query = "SELECT id, firstname, lastname, username, is_admin FROM users";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}
?>

<section class="dashboard">
    <!-- Success and error messages -->
    <?php if (isset($_SESSION['add-user-success'])) : ?>
        <div class="alert-message success container">
            <p>
                <?= $_SESSION['add-user-success'];
                unset($_SESSION['add-user-success']);
                ?>
            </p>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION['edit-user'])) : ?>
        <div class="alert-message error container">
            <p>
                <?= $_SESSION['edit-user'];
                unset($_SESSION['edit-user']);
                ?>
            </p>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION['edit-user-error'])) : ?>
        <div class="alert-message error container">
            <p>
                <?= $_SESSION['edit-user-error'];
                unset($_SESSION['edit-user-error']);
                ?>
            </p>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION['delete-user'])) : ?>
        <div class="alert-message error container">
            <p>
                <?= $_SESSION['delete-user'];
                unset($_SESSION['delete-user']);
                ?>
            </p>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION['delete-user-success'])) : ?>
        <div class="alert-message success container">
            <p>
                <?= $_SESSION['delete-user-success'];
                unset($_SESSION['delete-user-success']);
                ?>
            </p>
        </div>
    <?php endif ?>

    <div class="container dashboard-container">
        <button id="show-sidebar-btn" class="sidebar-toggle"><i class="uil uil-angle-right-b"></i></button>
        <button id="hide-sidebar-btn" class="sidebar-toggle"><i class="uil uil-angle-left-b"></i></button>
        <aside>
            <ul>
                <li>
                    <a href="add-post.php"><i class="uil uil-pen"></i>
                        <h5>Add Post</h5>
                    </a>
                </li>
                <li>
                    <a href="index.php"><i class="uil uil-postcard"></i>
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
                        <a href="manage-user.php" class="active"><i class="uil uil-users-alt"></i>
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
            <h2>Manage Users</h2>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Edit</th>
                            <th>Delete</th>
                            <th>Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                                <td><?= htmlspecialchars($user['username']); ?></td>
                                <td><a href="edit-user.php?id=<?= $user['id']; ?>" class="btn sm">Edit</a></td>
                                <td><a href="delete-user.php?id=<?= $user['id']; ?>" class="btn sm danger">Delete</a></td>
                                <td><?= $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <div class="alert-message error"><?= "No users found." ?></div>
            <?php endif ?>
        </main>
    </div>
</section>

<?php
include '../partials/footer.php';
?>
