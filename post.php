<?php include "includes/header.php" ?>
<?php include "includes/navigation.php" ?>

<?php
$postData = '';
$user_role = $_SESSION['user_role'];
$userArray = [];
$userData = readTable($connection, "users");
foreach ($userData as $userDataRow) {
    $name = $userDataRow['first_name'] . " " . $userDataRow['last_name'];
    $userArray[$userDataRow['user_id']] = $name;
}
?>

<?php
$postId = 0;
if (isset($_GET['p-id'])) {
    $postId = $_GET['p-id'];
    $postData = getRowById($connection, "posts", "post_id", $postId);
} else {
    header("Location: index.php");
}

if (isset($_POST['create_comment']) && isset($_POST['comment_author']) && isset($_POST['comment_email']) && isset($_POST['comment_content'])) {
    if (checkEmpty($_POST['comment_author']) || checkEmpty($_POST['comment_email']) || checkEmpty($_POST['comment_content'])) {
        displayAlert("danger", "Fields must not be empty");
    } else {
        $comment_author = $_POST['comment_author'];
        $comment_email = $_POST['comment_email'];
        $comment_content = $_POST['comment_content'];
        $addCommentStatus = addComment($connection, $postId, $comment_author, $comment_email, $comment_content);
        if ($addCommentStatus) {
            displayAlert("success", "Comment added successfully!");
        } else {
            displayAlert("danger", "Something went wrong, could not add comment!");
        }
    }
    // header("Location: /cms/post.php?p-id=$postId");
}
?>

<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">
            <?php
            if (isset($postData['post_status']) && shouldShowPost($postData['post_status'], $user_role)) {
                incPostView($connection, $postId);
            ?>

                <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <?php echo $postData['post_title'] ?>
                </h2>
                <p class="lead">
                    by <a href="author_posts.php?author=<?php echo $postData['post_author'] ?>"><?php echo $userArray[$postData['post_author']]; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $postData['post_date'] ?></p>
                <hr>
                <img class="img-responsive" src="images/<?php echo $postData['post_img'] ?>" alt="post-image">
                <hr>
                <p><?php echo $postData['post_content'] ?></p>
                <hr>

                <div class="well">
                    <h4>Leave a Comment:</h4><br>
                    <form role="form" action="" method="POST">
                        <div class="form-group">
                            <label for="comment_author">Comment Author</label>
                            <input type="text" name="comment_author" id="comment_author" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="comment_email">Author Email</label>
                            <input type="email" name="comment_email" id="comment_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="comment_content">Comment</label>
                            <textarea class="form-control" rows="3" name="comment_content" id="comment_content"></textarea>
                        </div>
                        <input type="submit" name="create_comment" class="btn btn-primary" value="Submit">
                    </form>
                </div>
                <hr>
                <?php
                $commentsApproaved = viewComments($connection, $postId);
                foreach ($commentsApproaved as $commentRow) { ?>
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" width="64" src="https://www.freeiconspng.com/uploads/homepage--office-of-the-university-registrar-14.png" alt="profile-image">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $commentRow['comment_author']; ?>
                                <small><?php echo $commentRow['comment_date']; ?></small>
                            </h4>
                            <?php echo $commentRow['comment_content']; ?>
                        </div>
                    </div><br>
                <?php } ?>
            <?php } else { ?>
                <h1>Post does not exist</h1>
            <?php } ?>

        </div>

        <?php include "includes/sidebar.php" ?>

    </div>
    <!-- /.row -->

    <hr>

    <?php include "includes/footer.php" ?>