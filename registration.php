<?php include "includes/header.php"; ?>

<!-- Navigation -->

<?php include "includes/navigation.php"; ?>

<!-- Register -->
<?php
if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (checkEmpty($username) || checkEmpty($first_name) || checkEmpty($last_name) || checkEmpty($email) || checkEmpty($password)) {
        displayAlert("danger", "All fields are required, it can't be empty!");
    } else {
        $username = mysqli_real_escape_string($connection, $username);
        $first_name = mysqli_real_escape_string($connection, $first_name);
        $last_name = mysqli_real_escape_string($connection, $last_name);
        $email = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);
        $newUser = [];
        $newUser['user_role'] = 'subscriber';
        $newUser['username'] = $username;
        $newUser['first_name'] = $first_name;
        $newUser['last_name'] = $last_name;
        $newUser['email'] = $email;
        $newUser['password'] = $password;
        $newUser['user_image'] = "";

        $status = createUser($connection, $newUser);
        if ($status) {
            displayAlert("success", "Registration has been successful!");
        } else {
            displayAlert("danger", "Something went wrong!");
        }
    }
}
?>


<!-- Page Content -->
<div class="container">

    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">
                    <div class="form-wrap">
                        <h1>Register</h1>
                        <form role="form" action="registration.php" method="post" id="login-form" autocomplete="off">
                            <div class="form-group">
                                <label for="username" class="sr-only">username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter Desired Username">
                            </div>
                            <div class="form-group">
                                <label for="first_name" class="sr-only">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter your first name">
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="sr-only">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter your last name">
                            </div>
                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="somebody@example.com">
                            </div>
                            <div class="form-group">
                                <label for="password" class="sr-only">Password</label>
                                <input type="password" name="password" id="key" class="form-control" placeholder="Password">
                            </div>

                            <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
                        </form>

                    </div>
                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>


    <hr>



    <?php include "includes/footer.php"; ?>