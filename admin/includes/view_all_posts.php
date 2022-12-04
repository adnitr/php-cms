<?php
if (isset($_POST['submit']) && $_SESSION['user_role'] === 'admin' && isset($_POST['postCheckBox']) && isset($_POST['bulkAction'])) {
    $action = $_POST['bulkAction'];
    $checkboxes = $_POST['postCheckBox'];
    if ($action === '') {
        displayAlert("danger", "Select an action to be taken (delete, draft, published).");
    } else {
        // echo $action;
        // print_r($checkboxes);
        $modifyStatus = true;
        foreach ($checkboxes as $checkboxId) {
            if ($action === 'published' || $action === 'draft') {
                $modStatus = modifyAField($connection, "posts", "post_id", $checkboxId, "post_status", $action);
                if (!$modStatus) {
                    $modifyStatus = false;
                }
            }
            if ($action === 'delete') {
                $modStatus = deletePost($connection, $checkboxId);
                if (!$modStatus) {
                    $modifyStatus = false;
                }
            }

            if ($action === 'clone') {
                $modStatus = clonePost($connection, $checkboxId);
                if (!$modStatus) {
                    $modifyStatus = false;
                }
            }

            if ($action === 'view_reset') {
                $modStatus = modifyAField($connection, "posts", "post_id", $checkboxId, "post_views_count", 0);
                if (!$modStatus) {
                    $modifyStatus = false;
                }
            }
        }
        if ($modifyStatus) {
            displayAlert("success", "Selected posts has been modified successfully!");
            $postData = readTable($connection, "posts");
        } else {
            displayAlert("danger", "Something went wrong, selected post(s) could not be modified!");
        }
    }
}
?>

<form action="" method="POST">
    <table class="table table-bordered table-hover">
        <?php include "modal.php"; ?>
        <div id="bulkOptionContainer" class="col-xs-4">
            <select name="bulkAction" id="bulkAction" class="form-control">
                <option value="">Select Option</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="view_reset">Reset Views</option>
                <option value="clone">Clone</option>
                <option value="delete">Delete</option>
            </select>
        </div>

        <div class="col-xs-4">
            <input onClick="javascript: return confirm('Are you sure you want to proceed with the selected action?');" type="submit" name="submit" value="Apply" class="btn btn-success">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
        </div>

        <thead>
            <tr>
                <th><input id="selectAllBoxes" type="checkbox"></th>
                <th>Id</th>
                <th>Author</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Images</th>
                <th>Tags</th>
                <th>Date</th>
                <th>Views</th>
                <th>Comments</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($postData as $postDataRow) { ?>
                <tr>
                    <td><input class="checkBoxes" type="checkbox" name="postCheckBox[]" value="<?php echo $postDataRow['post_id']; ?>"></td>
                    <td><?php echo $postDataRow['post_id']; ?></td>
                    <td><?php echo $userArray[$postDataRow['post_author']]; ?></td>
                    <td><a href="../post.php?p-id=<?php echo $postDataRow['post_id']; ?>"><?php echo $postDataRow['post_title']; ?></a></td>
                    <td><?php echo $catArray[$postDataRow['post_category_id']]; ?></td>
                    <td><?php echo $postDataRow['post_status']; ?></td>
                    <td> <img width="100" src="../images/<?php echo $postDataRow['post_img']; ?>" alt="post-img"></td>
                    <td><?php echo $postDataRow['post_tags']; ?></td>
                    <td><?php echo $postDataRow['post_date']; ?></td>
                    <td><?php echo $postDataRow['post_views_count']; ?></td>
                    <td><a href="comments.php?p-id=<?php echo $postDataRow['post_id']; ?>"><?php echo $postDataRow['post_comment_count']; ?></a></td>
                    <td><a href="posts.php?source=edit_post&edit=<?php echo $postDataRow['post_id']; ?>">Edit</a></td>
                    <!-- <td><a onclick="javascript: return confirm('Are you sure you want to delete this post?');" href="?delete=<?php echo $postDataRow['post_id']; ?>">Delete</a></td> -->
                    <td><a class="delete_link" rel="<?php echo $postDataRow['post_id']; ?>" href="javascript:void(0)">Delete</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</form>