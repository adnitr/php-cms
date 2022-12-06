<?php include "includes/header.php" ?>
<?php include "includes/navigation.php" ?>

<?php

//find the user role
$user_role = checkAdmin();

//initialize pageNo and noOfPostsPerPage
$pageNo = 1;
$noOfPostsPerPage = 5;

//initialize noOfPages
$noOfPages = 1;

//find the pageNo if provided via GET request
if (isset($_GET['page'])) {
    $pageNo = $_GET['page'];
}

//get and set the author variable
$author = '';
if (isset($_GET['author'])) {
    $author = $_GET['author'];
} else {
    header("Location: index.php");
}

$userRow = getRowById($connection, "users", "user_id", $author);
$name = $userRow['first_name'] . " " . $userRow['last_name'];

//find the val for reading table
$posts = [];
$postExists = false;
$val = ($pageNo - 1) * $noOfPostsPerPage;

//check user_role and find the noOfPages(noOfPosts/5) to be displayed
if ($user_role === 'admin') {
    $noOfPages = ceil(countRowsCondition($connection, "posts", array("post_author" => $author)) / 5);
    $query = "SELECT * FROM posts WHERE post_author = $author LIMIT $val, $noOfPostsPerPage";
    $data = mysqli_query($connection, $query);
    $posts = generateArray($data);
} else {
    $noOfPages = ceil(countRowsCondition($connection, "posts", array("post_author" => $author, "post_status" => "published")) / 5);
    $query = "SELECT * FROM posts WHERE post_author = $author AND post_status = 'published' LIMIT $val, $noOfPostsPerPage";
    $data = mysqli_query($connection, $query);
    $posts = generateArray($data);
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                All Posts By
                <small><?php echo $name; ?></small>
            </h1>

            <!-- First Blog Post -->
            <?php
            foreach ($posts as $postRow) {
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

    <ul class="pager">
        <?php
        for ($i = 1; $i <= $noOfPages; $i++) {
        ?>
            <?php if ($i == $pageNo) { ?>
                <li><a class="active_link" href="<?php echo "author_posts.php?author=$author&page=$i"; ?>"><?php echo $i; ?></a></li>
            <?php } else { ?>
                <li><a href="<?php echo "author_posts.php?author=$author&page=$i"; ?>"><?php echo $i; ?></a></li>
            <?php } ?>

        <?php } ?>
    </ul>

    <?php include "includes/footer.php" ?>