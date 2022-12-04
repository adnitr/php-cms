<?php

?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>First name</th>
            <th>Last name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Make Admin</th>
            <th>Make Subscriber</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($userData as $userDataRow) {
        ?>
            <tr>
                <td><?php echo $userDataRow['user_id']; ?></td>
                <td><?php echo $userDataRow['username']; ?></td>
                <td><?php echo $userDataRow['first_name']; ?></td>
                <td><?php echo $userDataRow['last_name']; ?></td>
                <td><?php echo $userDataRow['email']; ?></td>
                <td><?php echo $userDataRow['user_role']; ?></td>
                <td><a href="users.php?change_to_admin=<?php echo $userDataRow['user_id']; ?>">Admin</a></td>
                <td><a href="users.php?change_to_sub=<?php echo $userDataRow['user_id']; ?>">Subscriber</a></td>
                <td><a href="users.php?source=edit_user&edit=<?php echo $userDataRow['user_id']; ?>">Edit</a></td>
                <td><a href="users.php?delete=<?php echo $userDataRow['user_id']; ?>">Delete</a></td>
            </tr>
        <?php } ?>
    </tbody>
</table>