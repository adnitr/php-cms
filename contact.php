<?php include "includes/header.php"; ?>

<!-- Navigation -->

<?php include "includes/navigation.php"; ?>

<!-- Register -->
<?php
if (isset($_POST['submit_contact']) && isset($_POST['user_name']) && isset($_POST['user_email']) && isset($_POST['user_message'])) {
    $user_name = $_POST['user_name'];
    $user_email = $_POST['user_email'];
    $user_message = $_POST['user_message'];

    if (checkEmpty($user_name) || checkEmpty($user_email) || checkEmpty($user_message)) {
        displayAlert("danger", "All fields are required, it can't be empty!");
    } else {
        $user_name = mysqli_real_escape_string($connection, $user_name);
        $user_email = mysqli_real_escape_string($connection, $user_email);
        $user_message = mysqli_real_escape_string($connection, $user_message);

        // use wordwrap() if lines are longer than 70 characters
        $user_message = wordwrap($user_message, 70);

        //joining user email
        $user_message .= "\n\n\nUSER EMAIL => $user_email";

        // send email
        mail("adnitrkl@gmail.com", "CMS Query By $user_name", $user_message, "From: adarsh.aspire@gmail.com");
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
                        <h1 class="text-center">Contact Us</h1>
                        <form role="form" action="" method="post" id="login-form" autocomplete="off">
                            <div class="form-group">
                                <label for="user_email" class="sr-only">Email address</label>
                                <input type="email" name="user_email" id="user_email" class="form-control" placeholder="Enter your email">
                            </div>
                            <div class="form-group">
                                <label for="user_name" class="sr-only">Your Name</label>
                                <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Your name...">
                            </div>
                            <div class="form-group">
                                <label for="user_message" class="sr-only">Message</label>
                                <textarea name="user_message" id="user_message" cols="30" rows="10" placeholder="Your message..." class="form-control"></textarea>
                            </div>
                            <input type="submit" name="submit_contact" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Submit Query">
                        </form>

                    </div>
                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container -->
    </section>


    <hr>



    <?php include "includes/footer.php"; ?>