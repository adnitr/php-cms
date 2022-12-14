<?php include "includes/header.php" ?>

<?php

$user_role = checkAdmin();

if ($user_role === 'admin') {
    $noOfComments = countRowsInTable($connection, "comments");
    $noOfUsers = countRowsInTable($connection, "users");
    $noOfPosts = countRowsInTable($connection, "posts");
    $noOfCategories = countRowsInTable($connection, "categories");
    $noOfDraftPost = countRowsByColVal($connection, "posts", "post_status", "draft");
    $noOfSubscriber = countRowsByColVal($connection, "users", "user_role", "subscriber");
    $noOfUnapprComments = countRowsByColVal($connection, "comments", "comment_status", "unapproaved");
} else if ($user_role === 'subscriber') {
    $noOfComments = countCommentAuthorPosts();
    $noOfPosts = countAuthorPosts();
    $noOfCategories = countRowsInTable($connection, "categories");
    $noOfDraftPost = countAuthorDraftPosts();
    $noOfUnapprComments = countAuthorUnapprComments();
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
                        Welcome to <?php if (isAdmin()) {
                                        echo "Super Admin";
                                    } else {
                                        echo "Admin";
                                    } ?>
                        <small><?php echo $_SESSION['username']; ?></small>
                    </h1>
                </div>
            </div>
            <!-- /.row -->


            <!-- /.row -->

            <div class="row">
                <div class="col-lg-<?php if (isAdmin()) {
                                        echo '3';
                                    } else {
                                        echo '4';
                                    } ?> col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo $noOfPosts; ?></div>
                                    <div>Posts</div>
                                </div>
                            </div>
                        </div>
                        <a href="posts.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-<?php if (isAdmin()) {
                                        echo '3';
                                    } else {
                                        echo '4';
                                    } ?> col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo $noOfComments; ?></div>
                                    <div>Comments</div>
                                </div>
                            </div>
                        </div>
                        <a href="comments.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php if ($user_role === 'admin') { ?>
                    <div class="col-lg-<?php if (isAdmin()) {
                                            echo '3';
                                        } else {
                                            echo '4';
                                        } ?> col-md-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-user fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class='huge'><?php echo $noOfUsers; ?></div>
                                        <div> Users</div>
                                    </div>
                                </div>
                            </div>
                            <a href="users.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-lg-<?php if (isAdmin()) {
                                        echo '3';
                                    } else {
                                        echo '4';
                                    } ?> col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-list fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class='huge'><?php echo $noOfCategories; ?></div>
                                    <div>Categories</div>
                                </div>
                            </div>
                        </div>
                        <a href="<?php if (isAdmin()) {
                                        echo 'categories.php';
                                    } else {
                                        echo 'index.php';
                                    } ?>">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <?php if (checkAdmin() === 'admin') { ?>
                <div class="row">
                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Data', 'Count'],
                                <?php
                                echo "['All Posts', $noOfPosts],";
                                echo "['Active Posts', ($noOfPosts-$noOfDraftPost)],";
                                echo "['Draft Posts', $noOfDraftPost],";
                                echo "['Comments', $noOfComments],";
                                echo "['Pending Comments', $noOfUnapprComments],";
                                echo "['Users', $noOfUsers],";
                                echo "['Subscribers', $noOfSubscriber],";
                                echo "['Categories', $noOfCategories]";
                                ?>
                            ]);

                            var options = {
                                chart: {
                                    title: '',
                                    subtitle: '',
                                }
                            };

                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                    </script>
                    <div id="columnchart_material" style="width: 'auto'; height: 500px; margin-top: 25px;"></div>
                </div>
            <?php } else { ?>
                <div class="row">
                    <script type="text/javascript">
                        google.charts.load('current', {
                            'packages': ['bar']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable([
                                ['Data', 'Count'],
                                <?php
                                echo "['All Posts', $noOfPosts],";
                                echo "['Active Posts', ($noOfPosts-$noOfDraftPost)],";
                                echo "['Draft Posts', $noOfDraftPost],";
                                echo "['Comments', $noOfComments],";
                                echo "['Active Comments', ($noOfComments-$noOfUnapprComments)],";
                                echo "['Pending Comments', $noOfUnapprComments],";
                                echo "['Categories', $noOfCategories]";
                                ?>
                            ]);

                            var options = {
                                chart: {
                                    title: '',
                                    subtitle: '',
                                }
                            };

                            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                            chart.draw(data, google.charts.Bar.convertOptions(options));
                        }
                    </script>
                    <div id="columnchart_material" style="width: 'auto'; height: 500px; margin-top: 25px;"></div>
                </div>
            <?php } ?>



        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php include "includes/footer.php" ?>