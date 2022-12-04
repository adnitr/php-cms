<?php include "includes/header.php" ?>
<?php include "includes/navigation.php" ?>

<?php
$postsPerPage = 5;
$pageNo = 0;
$val1 = 0;
$noOfPosts = 0;
$postExists = false;
$isSearch = false;
$search = '';

$userArray = [];
$userData = readTable($connection, "users");
foreach ($userData as $userDataRow) {
    $name = $userDataRow['first_name'] . " " . $userDataRow['last_name'];
    $userArray[$userDataRow['user_id']] = $name;
}

if (isset($_GET['page'])) {
    $pageNo = $_GET['page'];
    $val1 = ($pageNo - 1) * $postsPerPage;
}

if (isset($_POST['search']) || isset($_GET['search'])) {
    if (isset($_POST['search'])) {
        $search = $_POST['search'];
    }
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
    }
    $isSearch = true;
    $query = "SELECT * FROM posts WHERE post_tags LIKE '%$search%'";

    $posts = mysqli_query($connection, $query);
    $postsarr = [];
    while ($row = mysqli_fetch_assoc($posts)) {
        array_push($postsarr, $row);
    }

    if (!$posts) {
        die('QUERY FAILED!' . mysqli_error($connection));
    } else {
        $noOfPosts = mysqli_num_rows($posts);
        $noOfPages = ceil($noOfPosts / $postsPerPage);
        $posts = array_slice($postsarr, $val1, $postsPerPage);
    }
} else {
    $noOfPosts = countRowsInTable($connection, "posts");
    $noOfPages = ceil($noOfPosts / $postsPerPage);
    $posts = readTableLimited($connection, "posts", $val1, $postsPerPage);
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <h1 class="page-header">
                <?php
                if ($search === '') {
                    echo 'Page Heading
                    <small>Secondary Text</small>';
                } else {
                    echo 'Showing result for <small>' . $search . '</small>';
                }
                ?>

            </h1>

            <!-- First Blog Post -->
            <?php
            foreach ($posts as $postRow) {
                if ($postRow['post_status'] === 'published') {
                    $postExists = true;
            ?>
                    <h2>
                        <a href="post.php?p-id=<?php echo $postRow['post_id'] ?>"><?php echo $postRow['post_title'] ?></a>
                    </h2>
                    <p class="lead">
                        by <a href="author_posts.php?author=<?php echo $postRow['post_author'] ?>"><?php echo $userArray[$postRow['post_author']]; ?></a>
                    </p>
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

    <ul class="pager">
        <?php
        for ($i = 1; $i <= $noOfPages; $i++) {
            if (!$isSearch) {
        ?>
                <?php if ($i == $pageNo) { ?>
                    <li><a class="active_link" href="<?php echo "index.php?page=$i"; ?>"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a href="<?php echo "index.php?page=$i"; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
            <?php } else { ?>
                <?php if ($i == $pageNo) { ?>
                    <li><a class="active_link" href="<?php echo "index.php?search=$search&page=$i"; ?>"><?php echo $i; ?></a></li>
                <?php } else { ?>
                    <li><a href="<?php echo "index.php?search=$search&page=$i"; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
        <?php }
        } ?>
    </ul>

    <?php include "includes/footer.php" ?>