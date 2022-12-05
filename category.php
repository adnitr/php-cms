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

//get and set the c-id variable
$cId = '';
if (isset($_GET['c-id'])) {
    $cId = $_GET['c-id'];
} else {
    header("Location: index.php");
}

//find the val for reading table
$posts = [];
$postExists = false;
$val = ($pageNo - 1) * $noOfPostsPerPage;

//check user_role and find the noOfPages(noOfPosts/5) to be displayed
if ($user_role === 'admin') {
    $noOfPages = ceil(countRowsCondition($connection, "posts", array("post_category_id" => $cId)) / 5);
    $query = "SELECT * FROM posts LEFT JOIN users ON post_author = user_id WHERE post_category_id = $cId LIMIT $val, $noOfPostsPerPage";
    $data = mysqli_query($connection, $query);
    $posts = generateArray($data);
} else {
    $noOfPages = ceil(countRowsCondition($connection, "posts", array("post_category_id" => $cId, "post_status" => "published")) / 5);
    $query = "SELECT * FROM posts LEFT JOIN users ON post_author = user_id WHERE post_category_id = $cId AND post_status = 'published' LIMIT $val, $noOfPostsPerPage";
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
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <?php
            foreach ($posts as $postRow) {
                $postExists = true;
            ?>
                <h2>
                    <a href="post.php?p-id=<?php echo $postRow['post_id'] ?>"><?php echo $postRow['post_title'] ?></a>
                </h2>
                <p class="lead">
                    by <a href="author_posts?author=<?php echo $postRow['post_author']; ?>"><?php echo $postRow['first_name'] . " " . $postRow['last_name']; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $postRow['post_date'] ?></p>
                <hr>
                <img class="img-responsive" src="images/<?php echo $postRow['post_img'] ?>" alt="post-image">
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
                <li><a class="active_link" href="<?php echo "category.php?c-id=$cId&page=$i"; ?>"><?php echo $i; ?></a></li>
            <?php } else { ?>
                <li><a href="<?php echo "category.php?c-id=$cId&page=$i"; ?>"><?php echo $i; ?></a></li>
            <?php } ?>

        <?php } ?>
    </ul>

    <?php include "includes/footer.php" ?>