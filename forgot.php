<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

<?php include "includes/navigation.php" ?>

<?php
require "includes/PHPMailer.php";
require "includes/SMTP.php";
require "includes/Exception.php";
require "classes/config.php";

//Define namespaces
use PHPMailer\PHPMailer\PHPMailer;
?>

<?php
if (isLoggedIn() || !isset($_GET['forgot'])) {
    redirect("index.php");
}

if (isset($_POST['recover-submit'])) {
    if (isset($_POST['email']) && !checkEmpty($_POST['email'])) {
        $email = $_POST['email'];
        if (isDuplicate($connection, "users", "email", $email)) {
            $token = bin2hex(openssl_random_pseudo_bytes(50));
            if ($stmt = mysqli_prepare($connection, "UPDATE users SET token = '{$token}' WHERE email = ?")) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                //configuration
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = Config::SMTP_HOST;
                $mail->SMTPAuth = "true";
                $mail->SMTPSecure = "tls";
                $mail->Port = Config::SMTP_PORT;
                $mail->Username = Config::SMTP_USER;
                $mail->Password = Config::SMTP_PASS;
                $mail->isHTML(true);
                $mail->CharSet = "UTF-8";

                //set email subject
                $mail->Subject = "Reset your password - CMS";

                //Set sender email
                $mail->setFrom("adnitrkl@gmail.com");

                //Email body
                $mail->Body = '<h1>Click on the link below to reset the password</h1><p>Password reset link: </p><a href="http://localhost/cms/reset.php?token=' . $token . '">http://localhost/cms/reset.php?token=' . $token . '</a>';

                //Add recipient
                $mail->addAddress($email);

                if ($mail->send()) {
                    displayAlert("success", "Email successfully sent. Check your inbox to reset the password.");
                } else {
                    displayAlert("danger", "Something went wrong");
                }
            } else {
                echo mysqli_stmt_error($stmt);
            }
        } else {
            displayAlert("danger", "Email does not exist in the database.");
        }
    } else {
        displayAlert("danger", "Email field can not be empty!");
    }
}

?>

<div class="container">

    <div class="form-gap"></div>
    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">


                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">




                                <form id="register-form" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="email" placeholder="email address" class="form-control" type="email">
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