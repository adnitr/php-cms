<?php
$editRow = '';
$editId = 0;

if (isset($_GET['edit']) && $_SESSION['user_role'] === 'admin') {
    $editId = $_GET['edit'];
    $editRow = getRowById($connection, "users", "user_id", $editId);
} else {
    header("Location: index.php");
}
if (isset($_POST['edit_user']) && $_SESSION['user_role'] === 'admin' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name'])) {
    $user_id = $editId;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_img = null;
    $user_img_temp = null;
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
        $editUserData['user_image'] = $user_img;
        $editStatus = editUser($connection, $editUserData);
        if ($editStatus) {
            displayAlert("success", "User Edited Successfully! <a href='users.php'>View all users<a>");
            $editRow = getRowById($connection, "users", "user_id", $editId);
        } else {
            displayAlert("danger", "Something went wrong, user could not be edited!");
        }
    }
}

?>


<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control" id="username" value="<?php echo $editRow['username']; ?>">
    </div>
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" class="form-control" id="first_name" value="<?php echo $editRow['first_name']; ?>">
    </div>
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" class="form-control" id="last_name" value="<?php echo $editRow['last_name']; ?>">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" id="email" value="<?php echo $editRow['email']; ?>">
    </div>
    <div class="form-group">
        <label for="password">Change Password</label>
        <input type="password" name="password" class="form-control" id="password">
    </div>
    <!-- <div class="form-group">
        <label for="user_img">Profile Picture</label>
        <input name="user_img" type="file" id="user_img">
    </div> -->
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_user" value="Edit User">
    </div>
</form>