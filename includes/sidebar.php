<!-- Blog Sidebar Widgets Column -->
<div class="col-md-4">
    <!-- Blog Search Well -->
    <div class="well">
        <h4>Blog Search</h4>
        <form action="index.php" method="POST">
            <div class="input-group">
                <input name="search" type="text" class="form-control">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit" name="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.input-group -->
    </div>

    <!-- Blog Categories Well -->
    <div class="well">
        <h4>Blog Categories</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <?php
                    $data = readTable($connection, "categories");
                    foreach ($data as $catRow) { ?>
                        <li><a href="category.php?c-id=<?php echo $catRow['cat_id']; ?>"><?php echo $catRow['cat_title'] ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- /.col-lg-6 -->
        </div>
        <!-- /.row -->
    </div>

    <!-- Login -->
    <div class="well">
        <h4>Login</h4>
        <form action="includes/login.php" method="POST">
            <div class="form-group">
                <input name="username" type="text" class="form-control" placeholder="Enter username..." required>
            </div>
            <div class="input-group">
                <input name="password" type="password" class="form-control" placeholder="Enter password" required>
                <span class="input-group-btn">
                    <input class="btn btn-primary" type="submit" name="login_submit" value="Login">
                </span>
            </div>
        </form>
    </div>

    <?php include "widget.php"; ?>

</div>