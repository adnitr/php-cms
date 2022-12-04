<?php include "includes/header.php" ?>

<?php
$userData = readTable($connection, "users");
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
                    if (isset($_GET['delete'])  && $_SESSION['user_role'] === 'admin') {
                        $deleteId = $_GET['delete'];
                        $status = deleteUser($connection, $deleteId);
                        if ($status) {
                            displayAlert("success", "User deleted successfully!");
                            $userData = readTable($connection, "users");
                        } else {
                            displayAlert("danger", "Something went wrong, user could not be deleted.");
                        }
                    }

                    if (isset($_GET['change_to_admin']) && $_SESSION['user_role'] === 'admin') {
                        $userId = $_GET['change_to_admin'];
                        $query = "UPDATE users SET user_role = 'admin' WHERE user_id = '{$userId}'";
                        $status = mysqli_query($connection, $query);
                        if ($status) {
                            displayAlert("success", "User role changed to admin!");
                            $userData = readTable($connection, "users");
                        } else {
                            displayAlert("danger", "Something went wrong, user role could not be changed.");
                        }
                    }

                    if (isset($_GET['change_to_sub']) && $_SESSION['user_role'] === 'admin') {
                        $userId = $_GET['change_to_sub'];
                        $query = "UPDATE users SET user_role = 'subscriber' WHERE user_id = '{$userId}'";
                        $status = mysqli_query($connection, $query);
                        if ($status) {
                            displayAlert("success", "User role changed to subscriber!");
                            $userData = readTable($connection, "users");
                        } else {
                            displayAlert("danger", "Something went wrong, user role could not be changed.");
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
                        case 'edit_user':
                            include "includes/edit_user.php";
                            break;
                        case 'add_user':
                            include "includes/add_user.php";
                            break;
                        default:
                            include "includes/view_all_users.php";
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