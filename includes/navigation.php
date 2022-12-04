<!-- Navigation -->
<?php session_start(); ?>
<?php include "includes/db.php" ?>
<?php include "includes/functions.php" ?>

<?php
$categories = readTable($connection, "categories");
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">CMS</a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php
                foreach ($categories as $catRow) {
                    $catTitle = $catRow['cat_title'];
                    $catId = $catRow['cat_id'];
                    echo '<li><a href="category.php?c-id=' . $catId . '">' . $catTitle . '</a></li>';
                }
                ?>
                <li><a href="admin">Admin</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="registration.php">Register</a></li>
                <?php
                if (isset($_SESSION['user_role'])) {
                    if ($_SESSION['user_role'] === 'admin') {
                        if (isset($_GET['p-id'])) {
                            echo '<li><a href="admin/posts.php?source=edit_post&edit=' . $_GET['p-id'] . '">Edit Post</a></li>';
                        }
                    }
                }
                ?>

            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>