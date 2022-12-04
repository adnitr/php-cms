<?php include "db.php"; ?>
<?php include "functions.php"; ?>
<?php session_start(); ?>

<?php
if (isset($_POST['login_submit']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (checkEmpty($username) && checkEmpty(($password))) {
        // displayAlert("danger", "Username and Password fields are required!");
        header("Location: ../index.php");
    } else {
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);

        $query = "SELECT * FROM users WHERE username = '{$username}'";
        $status = mysqli_query($connection, $query);
        $status = mysqli_fetch_assoc($status);

        if (!$status) {
            // displayAlert("danger", "user not found!");
            header("Location: ../index.php");
        } else {
            if (password_verify($password, $status['password'])) {
                $_SESSION['username'] = $status['username'];
                $_SESSION['user_id'] = $status['user_id'];
                $_SESSION['first_name'] = $status['first_name'];
                $_SESSION['last_name'] = $status['last_name'];
                $_SESSION['user_role'] = $status['user_role'];
                $_SESSION['email'] = $status['email'];
                header("Location: ../admin");
                // displayAlert("success", "Login Successful!");
            } else {
                // displayAlert("danger", "Password do not match!");
                header("Location: ../index.php");
            }
        }
    }
}
?>