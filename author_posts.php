<?php include "includes/header.php" ?>
<?php include "includes/navigation.php" ?>

<?php
$user_role = $_SESSION['user_role'];
$userArray = [];
$userData = readTable($connection, "users");
foreach ($userData as $userDataRow) {
    $name = $userDataRow['first_name'] . " " . $userDataRow['last_name'];
    $userArray[$userDataRow['user_id']] = $name;
}
$posts = [];
$author = '';
if (isset($_GET['author'])) {
    $author = $_GET['author'];
    $query = "SELECT * FROM posts WHERE post_author = $author";
    $results = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($results)) {
        array_push($posts, $row);
    }
} else {
    header("Location: index.php");
}

$postExists = false;
?>

<?php
if (isset($_POST['search'])) {
    $search = $_POST['search'];

    $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search%'";

    $posts = mysqli_query($connection, $query);

    if (!$posts) {
        die('QUERY FAILED!' . mysqli_error($connection));
    }
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                All Posts By
                <small><?php echo $userArray[$author]; ?></small>
            </h1>

            <!-- First Blog Post -->
            <?php
            foreach ($posts as $postRow) {
                if (shouldShowPost($postRow['post_status'], $user_role)) {
                    $postExists = true;
            ?>
                    <h2>
                        <a href="post.php?p-id=<?php echo $postRow['post_id'] ?>"><?php echo $postRow['post_title'] ?></a>
                    </h2>
                    <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $postRow['post_date'] ?></p>
                    <hr>
                    <a href="post.php?p-id=<?php echo $postRow['post_id'] ?>"><img class="img-responsive" src="images/<?php echo $postRow['post_img'] ?>" alt="post-image"></a>

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