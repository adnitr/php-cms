<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<!-- Navigation -->

<?php include "includes/navigation.php"; ?>

<?php
$token = '';
if (isset($_GET['token']) && !isLoggedIn()) {
    $token = $_GET['token'];
    if (isset($_POST['recover-submit'])) {
        if (isset($_POST['new_pass']) && isset($_POST['new_pass_conf'])) {
            $new_pass = $_POST['new_pass'];
            $new_pass_conf = $_POST['new_pass_conf'];
            if ($new_pass === $new_pass_conf) {
                $user = getRowById($connection, "users", "token", $token);
                if (!$user) {
                    displayAlert("danger", "Invalid token!");
                } else {
                    $hashedPass = hashPassword($new_pass);
                    $user_id = $user['user_id'];
                    $query = "UPDATE users SET password = '$hashedPass', token = '' WHERE user_id = $user_id";
                    $status = mysqli_query($connection, $query);
                    if ($status) {
                        displayAlert("success", "Password has been changed successfully. <a href='login.php'>Login</a>");
                    } else {
                        displayAlert("danger", "Something went wrong. Try again later.");
                    }
                }
            } else {
                displayAlert("danger", "Passwords do not match. Try again.");
            }
        } else {
            displayAlert("danger", "Both field must be filled!");
        }
    }
} else {
    redirect("index.php");
}
?>

<!-- Page Content -->
<div class="container">

    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Reset Password</h2>
                            <div class="panel-body">

                                <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="new_pass" name="new_pass" placeholder="new password" class="form-control" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="new_pass_conf" name="new_pass_conf" placeholder="confirm password" class="form-control" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                    </div>

                                    <input type="hidden" class="hide" name="token" id="token" value="">
                                </form>

                            </div><!-- Body-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php"; ?>

</div> <!-- /.container -->