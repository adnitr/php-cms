<?php include "includes/header.php" ?>
<?php include "includes/navigation.php" ?>

<?php
$userArray = [];
$userData = readTable($connection, "users");
foreach ($userData as $userDataRow) {
    $name = $userDataRow['first_name'] . " " . $userDataRow['last_name'];
    $userArray[$userDataRow['user_id']] = $name;
}
$posts = readTable($connection, "posts");
$postExists = false;
?>

<?php
$posts = '';
if (isset($_GET['c-id'])) {
    $cId = $_GET['c-id'];

    $posts = fetchPostsByCategory($connection, $cId);
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <?php
            foreach ($posts as $postRow) {
                if ($postRow['post_status'] == "published") {
                    $postExists = true;
            ?>
                    <h2>
                        <a href="post.php?p-id=<?php echo $postRow['post_id'] ?>"><?php echo $postRow['post_title'] ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts?author=<?php echo $postRow['post_author']; ?>"><?php echo $userArray[$postRow['post_author']]; ?></a>
                    </p>
                    <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $postRow['post_date'] ?></p>
                    <hr>
                    <img class="img-responsive" src="images/<?php echo $postRow['post_img'] ?>" alt="post-image">
                    <hr>
                    <p><?php echo substring(strip_tags($postRow['post_content'])); ?></p>
                    <a class="btn btn-primary" href="post.php?p-id=<?php echo $postRow['post_id'] ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
                    <hr>

                <?php } ?>
            <?php }
            if (!$postExists) {
                echo '<h1>No post to display!</h1>';
            }
            ?>
        </div>

        <?php include "includes/sidebar.php" ?>

    </div>
    <!-- /.row -->

    <hr>

    <?php include "includes/footer.php" ?>