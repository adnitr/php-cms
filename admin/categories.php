<?php include "includes/header.php" ?>

<?php
$catData = readTable($connection, "categories");
$deleteSt = [];
$editSt = [];
?>

<?php
if (isset($_GET['delete']) && $_SESSION['user_role'] === 'admin') {
    $deleteStatus = deleteRow($connection, "categories", $_GET['delete']);
    if ($deleteStatus) {
        $deleteSt['status'] = 'success';
        $deleteSt['message'] = 'Category deleted successfully!!';
        $catData = readTable($connection, "categories");
    } else {
        $deleteSt['status'] = 'danger';
        $deleteSt['message'] = "Something went wrong, can't delete!";
    }
}
?>

<?php
if (isset($_GET['edit']) && $_SESSION['user_role'] === 'admin') {
    $editSt['id'] = $_GET['edit'];
    $editRow = getRowById($connection, "categories", "cat_id", $editSt['id']);
    $editSt['title'] = $editRow['cat_title'];
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

                    <div class="col-xs-6">
                        <?php include "includes/insert_categories.php"; ?>
                        <?php include "includes/edit_categories.php"; ?>

                    </div>

                    <div class="col-xs-6">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Title</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($catData as $catDataRow) { ?>
                                    <tr>
                                        <td><?php echo $catDataRow["cat_id"] ?></td>
                                        <td><?php echo $catDataRow["cat_title"] ?></td>
                                        <td><a href="categories.php?edit=<?php echo $catDataRow["cat_id"] ?>">Edit</a></td>
                                        <td><a href="categories.php?delete=<?php echo $catDataRow["cat_id"] ?>">Delete</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <?php
                        if (isset($deleteSt['message']) && isset($deleteSt['status'])) {
                            displayAlert($deleteSt['status'], $deleteSt['message']);
                        }
                        ?>
                    </div>
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