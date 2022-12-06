<?php
$editRow = '';

if (isset($_GET['edit'])) {
    $editId = $_GET['edit'];
    $editRow = getRowById($connection, "posts", "post_id", $editId);
}

if (!isAuthorOfPost($editId)) {
    redirect("../index.php");
    return;
}

if (isset($_POST['post_id']) && $_SESSION['user_role'] && isset($_POST['edit_post']) && isset($_POST['post_title']) && isset($_POST['post_category_id']) && isset($_POST['post_author']) && isset($_POST['post_tags']) && isset($_POST['post_content'])) {
    $post_id = $_POST['post_id'];
    $post_title = $_POST['post_title'];
    $post_category_id = $_POST['post_category_id'];
    $post_author = $_POST['post_author'];
    $post_status = $_POST['post_status'];
    $post_img = $_FILES['post_img']['name'];
    $post_img_temp = $_FILES['post_img']['tmp_name'];
    $post_tags = $_POST['post_tags'];
    $post_content = $_POST['post_content'];

    if (checkEmpty($post_title) || checkEmpty($post_category_id) || checkEmpty($post_author) || checkEmpty($post_tags) || checkEmpty($post_content)) {
        displayAlert("danger", "All fields are required!!");
    } else {
        $post_date = date('d-m-y');
        if (checkEmpty($post_img)) {
            $post_img = $editRow['post_img'];
        } else {
            move_uploaded_file($post_img_temp, "../images/$post_img");
        }

        $editPostData = [];
        $editPostData['post_category_id'] = $post_category_id;
        $editPostData['post_title'] = $post_title;
        $editPostData['post_author'] = $post_author;
        $editPostData['post_status'] = $post_status;
        $editPostData['post_img'] = $post_img;
        $editPostData['post_tags'] = $post_tags;
        $editPostData['post_content'] = $post_content;
        $editPostData['post_date'] = $post_date;
        if (isAuthorOfPost($editId)) {
            $editStatus = editPost($connection, $post_id, $editPostData);
            if ($editStatus) {
                displayAlert("success", "Post Edited Successfully!");
                $editRow = getRowById($connection, "posts", "post_id", $editId);
            } else {
                displayAlert("danger", "Something went wrong, post could not be edited!");
            }
        }
    }
}
?>



<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="post_title">Post Title</label>
        <input type="text" name="post_title" class="form-control" id="post_title" value="<?php echo $editRow['post_title']; ?>">
    </div>
    <div class="form-group">
        <label for="post_category_id">Post Category</label><br>

        <select name="post_category_id" id="post_category_id">
            <?php
            foreach ($catArray as $key => $value) { ?>
                <?php
                if ($editRow['post_category_id'] == $key) { ?>
                    <option value="<?php echo $key; ?>" selected><?php echo $value; ?></option>
                <?php } else { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>

            <?php } ?>
        </select>

    </div>

    <div class="form-group">
        <label for="post_author">Post Author</label>

        <select name="post_author" id="post_author" class="form-control" id="post_author">
            <option value="">Select Author</option>
            <?php
            foreach ($userData as $userDataRow) {
                $name = $userDataRow['first_name'] . " " . $userDataRow['last_name'];
                if ($editRow['post_author'] == $userDataRow['user_id']) {
                    echo '<option value="' . $userDataRow['user_id'] . '" selected>' . $name . '</option>';
                } else {
                    echo '<option value="' . $userDataRow['user_id'] . '">' . $name . '</option>';
                }
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="post_status">Post Status</label><br>

        <select name="post_status" id="post_status">
            <?php if ($editRow['post_status'] === 'draft') { ?>
                <option value="published">Published</option>
                <option value="draft" selected>Draft</option>
            <?php } else { ?>
                <option value="published" selected>Published</option>
                <option value="draft">Draft</option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group" style="display:none;">
        <label for="post_id">Post Id</label>
        <input type="text" name="post_id" class="form-control" id="post_id" value="<?php echo $editRow['post_id']; ?>">
    </div>
    <div class="form-group">
        <label for="post_img">Post Image</label><br>
        <img width="100" src="../images/<?php echo $editRow['post_img']; ?>" alt="post_img">
    </div>
    <div class="form-group">
        <input name="post_img" type="file" id="post_img_edit">
    </div>
    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" name="post_tags" class="form-control" id="post_tags" value="<?php echo $editRow['post_tags']; ?>">
    </div>
    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea type="text" id="summernote" name="post_content" class="form-control" id="post_content" cols="30" rows="10"><?php echo $editRow['post_content']; ?></textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_post" value="Edit Post">
    </div>
</form>