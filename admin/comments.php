<?php include "includes/header.php" ?>

<?php
$pId = 0;
if (isset($_GET['p-id'])) {
    $pId = $_GET['p-id'];
}

if ($pId != 0) {
    $commentData = readTableCondition($connection, "comments", "comment_post_id = $pId");
} else {
    $commentData = readTable($connection, "comments");
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
                    if (isset($_GET['delete']) && $_SESSION['user_role'] && (isCommentToAuthorPost($_GET['delete']) || isAdmin() || isMyComment($_GET['delete']))) {
                        $deleteId = $_GET['delete'];
                        $commentDataIndividual = getRowById($connection, "comments", "comment_id", $deleteId);
                        $status = deleteComment($connection, $deleteId);
                        decrementCommentCount($connection, $commentDataIndividual['comment_post_id']);
                        if ($status) {
                            displayAlert("success", "Comment deleted successfully!");
                            if ($pId != 0) {
                                $commentData = readTableCondition($connection, "comments", "comment_post_id = $pId");
                            } else {
                                $commentData = readTable($connection, "comments");
                            }
                        } else {
                            displayAlert("danger", "Something went wrong, comment could not be deleted.");
                        }
                    }

                    if (isset($_GET['approave']) && $_SESSION['user_role'] && (isCommentToAuthorPost($_GET['approave']) || isAdmin())) {
                        $approaveId = $_GET['approave'];
                        $status = approaveComment($connection, $approaveId);
                        if ($status) {
                            displayAlert("success", "Comment approaved successfully!");
                            if ($pId != 0) {
                                $commentData = readTableCondition($connection, "comments", "comment_post_id = $pId");
                            } else {
                                $commentData = readTable($connection, "comments");
                            }
                        } else {
                            displayAlert("danger", "Something went wrong, comment could not be approaved.");
                        }
                    }

                    if (isset($_GET['unapproave']) && $_SESSION['user_role'] && (isCommentToAuthorPost($_GET['unapproave']) || isAdmin())) {
                        $unapproaveId = $_GET['unapproave'];
                        $status = unapproaveComment($connection, $unapproaveId);
                        if ($status) {
                            displayAlert("success", "Comment unapproaved successfully!");
                            if ($pId != 0) {
                                $commentData = readTableCondition($connection, "comments", "comment_post_id = $pId");
                            } else {
                                $commentData = readTable($connection, "comments");
                            }
                        } else {
                            displayAlert("danger", "Something went wrong, comment could not be unapproaved.");
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
                        default:
                            include "includes/view_all_comments.php";
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