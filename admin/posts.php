<?php include "includes/header.php" ?>

<?php
$postData = readTable($connection, "posts");
$catArray = [];
$userArray = [];
$catData = readTable($connection, "categories");
foreach ($catData as $catDataRow) {
    $catArray[$catDataRow['cat_id']] = $catDataRow['cat_title'];
}
$userData = readTable($connection, "users");
foreach ($userData as $userDataRow) {
    $name = $userDataRow['first_name'] . " " . $userDataRow['last_name'];
    $userArray[$userDataRow['user_id']] = $name;
}
?>

<div id="wrapper">

    <!-- Navigation -->
    <?php include "includes/navigation.php" ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Welcome to Admin
                        <small>Author</small>
                    </h1>
                    <?php
                    if (isset($_GET['delete']) && isAuthorOfPost($postId)) {
                        $deleteId = $_GET['delete'];
                        $status = deletePost($connection, $deleteId);
                        if ($status) {
                            displayAlert("success", "Post deleted successfully!");
                            $postData = readTable($connection, "posts");
                        } else {
                            displayAlert("danger", "Something went wrong, post could not be deleted.");
                        }
                    }
                    ?>
                    <?php
                    if (isset($_GET['source'])) {
                        $source = ($_GET['source']);
                    } else {
                        $source = '';
                    }
                    switch ($source) {
                        case 'add_post':
                            include "includes/add_post.php";
                            break;
                        case 'edit_post':
                            include "includes/edit_post.php";
                            break;
                        default:
                            include "includes/view_all_posts.php";
                    }
                    ?>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php include "includes/footer.php" ?>