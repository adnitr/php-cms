<?php include "includes/header.php" ?>



<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
} else {
    if (!$_SESSION['user_id']) {
        header("Location: ../index.php");
    }
}

if (isset($_POST['edit_user_individual']) && $_SESSION['user_role'] === 'admin' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $password = $_POST['password'];

    if (checkEmpty($username) || checkEmpty($first_name) || checkEmpty($last_name) || checkEmpty($email)) {
        displayAlert("danger", "All fields are required!!");
    } else {
        // move_uploaded_file($user_img_temp, "../images/$user_img");
        $editUserData = [];
        $editUserData['user_id'] = $user_id;
        $editUserData['username'] = $username;
        $editUserData['first_name'] = $first_name;
        $editUserData['last_name'] = $last_name;
        $editUserData['email'] = $email;
        $editUserData['password'] = $password;
        if (isDuplicate($connection, "users", "username", $username)) {
            displayAlert("danger", "Username already exists");
        } else {
            if (isDuplicate($connection, "users", "email", $email)) {
                displayAlert("danger", "Email already exists");
            } else {
                $editStatus = editProfile($connection, $editUserData);
                if ($editStatus) {
                    displayAlert("success", "User Edited Successfully!");
                    $updatedUserData = getRowById($connection, "users", "user_id", $_SESSION['user_id']);
                    $_SESSION['username'] = $updatedUserData['username'];
                    $_SESSION['first_name'] = $updatedUserData['first_name'];
                    $_SESSION['last_name'] = $updatedUserData['last_name'];
                    $_SESSION['email'] = $updatedUserData['email'];
                } else {
                    displayAlert("danger", "Something went wrong, user could not be edited!");
                }
            }
        }
    }
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
                        <small><?php echo $_SESSION['username']; ?></small>
                    </h1>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control" id="username" value="<?php echo $_SESSION['username']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" id="first_name" value="<?php echo $_SESSION['first_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" id="last_name" value="<?php echo $_SESSION['last_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" value="<?php echo $_SESSION['email']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Change Password</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>
                        <div class="form-group">
                            <label for="user_role">User Role</label><br>
                            <select class="form-select" name="user_role" id="user_role" aria-label="Disabled select example" disabled>
                                <?php if ($_SESSION['user_role'] === 'admin') { ?>
                                    <option value="admin" selected>Admin</option>
                                    <option value="subscriber">Subscriber</option>
                                <?php } else { ?>
                                    <option value="admin">Admin</option>
                                    <option value="subscriber" selected>Subscriber</option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="edit_user_individual" value="Edit">
                        </div>
                    </form>


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