<?php

if (isset($_POST['create_user']) && $_SESSION['user_role'] === 'admin' && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['password']) && isset($_POST['user_role'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $user_img = null;
    $user_img_temp = null;
    $password = $_POST['password'];
    $user_role = $_POST['user_role'];

    if (checkEmpty($username) || checkEmpty($first_name) || checkEmpty($last_name) || checkEmpty($email) || checkEmpty($password) || checkEmpty($user_role)) {
        displayAlert("danger", "All fields are required!!");
    } else {
        // move_uploaded_file($user_img_temp, "../images/$user_img");
        $createUserData = [];
        $createUserData['username'] = $username;
        $createUserData['first_name'] = $first_name;
        $createUserData['last_name'] = $last_name;
        $createUserData['email'] = $email;
        $createUserData['password'] = $password;
        $createUserData['user_role'] = $user_role;
        $createUserData['user_image'] = $user_img;
        if (isDuplicate($connection, "users", "username", $username)) {
            displayAlert("danger", "Username already exists");
        } else {
            if (isDuplicate($connection, "users", "email", $email)) {
                displayAlert("danger", "Email already exists");
            } else {
                if (isDuplicate($connection, "users", "username", $username)) {
                    displayAlert("danger", "Email already exists");
                } else {
                    $createStatus = createUser($connection, $createUserData);
                    if ($createStatus) {
                        displayAlert("success", "User Added Successfully!");
                    } else {
                        displayAlert("danger", "Something went wrong, user could not be added!");
                    }
                }
            }
        }
    }
}

?>


<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control" id="username">
    </div>
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" class="form-control" id="first_name">
    </div>
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" class="form-control" id="last_name">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" class="form-control" id="email">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" id="password">
    </div>
    <div class="form-group">
        <label for="user_img">Profile Picture</label>
        <input name="user_img" type="file" id="user_img">
    </div>
    <div class="form-group">
        <label for="user_role">User Role</label><br>
        <select name="user_role" id="user_role">
            <option value="admin">Admin</option>
            <option value="subscriber">Subscriber</option>
        </select>
    </div>

    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_user" value="Create User">
    </div>
</form>