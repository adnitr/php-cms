<?php include "includes/header.php" ?>
<?php include "includes/navigation.php" ?>

<?php
$userId = 0;
$postData = '';
$user_role = checkAdmin();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
}

if (isset($_POST['liked'])) {
    $liked = $_POST['liked'];
    $post_id_likes = $_POST['post_id'];
    $user_id_likes = $_POST['user_id'];

    //find if user has logged in or not
    if ($user_id_likes == 0) {
        displayAlert("danger", "Please login to perform like/dislike action.");
        return;
    }

    if ($liked) {
        if (isLiked($connection, $post_id_likes)) {
            //user has already liked the post
            displayAlert("danger", "User has already liked the post");
        } else {
            //user has not already liked it
            //increment the post like count in posts table 
            $query = "UPDATE posts SET post_likes = post_likes + 1 WHERE post_id = '$post_id_likes'";
            $status = mysqli_query($connection, $query);
            if ($status) {
                $query = "INSERT INTO likes(post_id, user_id) VALUES('$post_id_likes', '$user_id_likes')";
                $status = mysqli_query($connection, $query);
                if ($status) {
                    displayAlert("success", "Successfully added the like");
                } else {
                    displayAlert("danger", "Something went wrong");
                }
            } else {
                displayAlert("danger", "Something went wrong");
            }
        }
    } else {
        if (isLiked($connection, $post_id_likes)) {
            //user has already liked the post
            //increment the post like count in posts table 
            $query = "UPDATE posts SET post_likes = post_likes - 1 WHERE post_id = '$post_id_likes'";
            $status = mysqli_query($connection, $query);
            if ($status) {
                $query = "DELETE FROM likes WHERE post_id = '$post_id_likes' AND user_id = '$user_id_likes'";
                $status = mysqli_query($connection, $query);
                if ($status) {
                    displayAlert("success", "Successfully disliked!");
                } else {
                    displayAlert("danger", "Something went wrong");
                }
            } else {
                displayAlert("danger", "Something went wrong");
            }
        } else {
            //user has not already liked it
            displayAlert("danger", "User has not liked the post yet. Can't perform dislike.");
        }
    }
}
?>

<?php
$postId = 0;
if (isset($_GET['p-id'])) {
    $postId = $_GET['p-id'];
    $query = "SELECT * FROM posts LEFT JOIN users ON post_author = user_id WHERE post_id = $postId";
    $data = mysqli_query($connection, $query);
    $postData = mysqli_fetch_assoc($data);
} else {
    header("Location: index.php");
}

if (isset($_POST['create_comment']) && isLoggedIn() && isset($_POST['comment_content'])) {
    if (checkEmpty($_POST['comment_content'])) {
        displayAlert("danger", "Fields must not be empty");
    } else {
        if (!isAlreadyCommented($postId)) {
            $userDetails = getRowById($connection, "users", "user_id", $_SESSION['user_id']);
            $comment_author = $userDetails['first_name'] . " " . $userDetails['last_name'];
            $comment_email = $userDetails['email'];
            $comment_content = $_POST['comment_content'];
            $addCommentStatus = addComment($connection, $postId, $comment_author, $comment_email, $comment_content, $userDetails['user_id']);
            if ($addCommentStatus) {
                displayAlert("success", "Comment added successfully!");
            } else {
                displayAlert("danger", "Something went wrong, could not add comment!");
            }
        } else {
            displayAlert("danger", "Already commented on this post!");
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
                    by <a href="author_posts.php?author=<?php echo $postData['post_author'] ?>"><?php echo $postData['first_name'] . " " . $postData['last_name']; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $postData['post_date'] ?></p>
                <hr>
                <img class="img-responsive" src="images/<?php echo $postData['post_img'] ?>" alt="post-image">
                <hr>
                <p><?php echo $postData['post_content'] ?></p>
                <hr>
                <?php if (!isLoggedIn()) {
                    echo '<div class="row">
                    <p class="pull-right">Please <a href="login.php">Login</a> to like the post.</p>
                </div>';
                } else {
                ?>
                    <?php if (isLiked($connection, $postId)) { ?>
                        <div class="row">
                            <p class="pull-right"><a href="" class="unlike"><span class="glyphicon glyphicon-thumbs-down" data-toggle="tooltip" data-placement="top" title="I don't like it anymore"></span> Unlike</a></p>
                        </div>
                    <?php } else { ?>
                        <div class="row">
                            <p class="pull-right"><a href="" class="like"><span class="glyphicon glyphicon-thumbs-up" data-toggle="tooltip" data-placement="top" title="I want to like it"></span> Like</a></p>
                        </div>
                <?php }
                } ?>
                <div class="row">
                    <p class="pull-right">Like: <?php echo getPostLikes($postId); ?></p>
                </div>
                <div class="clearfix"></div>

                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <?php if (isLoggedIn()) { ?>
                        <form role="form" action="" method="POST">
                            <!-- <div class="form-group">
                            <label for="comment_author">Comment Author</label>
                            <input type="text" name="comment_author" id="comment_author" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="comment_email">Author Email</label>
                            <input type="email" name="comment_email" id="comment_email" class="form-control" required>
                        </div> -->
                            <div class="form-group">
                                <!-- <label for="comment_content">Comment</label> -->
                                <textarea class="form-control" rows="3" name="comment_content" id="comment_content"></textarea>
                            </div>
                            <input type="submit" name="create_comment" class="btn btn-primary" value="Submit">
                        </form>
                    <?php } else { ?>
                        <p>Please <a href="login.php">login</a> to add comment to this post</p>
                    <?php } ?>
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

    <script>
        $(document).ready(function() {
            var post_id = <?php echo $postId; ?>;
            var user_id = <?php echo $userId; ?>;

            $("[data-toggle='tooltip']").tooltip();

            $('.like').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: `post.php?p-id=${post_id}`,
                    type: 'post',
                    data: {
                        'liked': 1,
                        'post_id': post_id,
                        'user_id': user_id,
                    }
                })
                location.reload();
            });

            $('.unlike').click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: `post.php?p-id=${post_id}`,
                    type: 'post',
                    data: {
                        'liked': 0,
                        'post_id': post_id,
                        'user_id': user_id,
                    }
                })
                location.reload();
            });


        });
    </script>