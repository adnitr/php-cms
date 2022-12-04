<?php

if (isset($_POST['create_post']) && $_SESSION['user_role'] === 'admin' && isset($_POST['post_title']) && isset($_POST['post_category_id']) && isset($_POST['post_author']) && isset($_POST['post_tags']) && isset($_POST['post_content'])) {
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
        move_uploaded_file($post_img_temp, "../images/$post_img");
        $addPostData = [];
        $addPostData['post_category_id'] = $post_category_id;
        $addPostData['post_title'] = $post_title;
        $addPostData['post_author'] = $post_author;
        $addPostData['post_status'] = $post_status;
        $addPostData['post_img'] = $post_img;
        $addPostData['post_comment_count'] = 0;
        $addPostData['post_tags'] = $post_tags;
        $addPostData['post_content'] = $post_content;
        $addPostData['post_date'] = $post_date;
        $addStatus = addPost($connection, $addPostData);
        if ($addStatus) {
            displayAlert("success", "Post Added Successfully!");
        } else {
            displayAlert("danger", "Something went wrong, post could not be added!");
        }
    }
}

?>


<form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="post_title">Post Title</label>
        <input type="text" name="post_title" class="form-control" id="post_title">
    </div>
    <div class="form-group">
        <label for="post_category_id">Post Category</label><br>

        <select name="post_category_id" id="post_category_id" class="form-control">
            <?php
            foreach ($catArray as $key => $value) { ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="form-group">
        <label for="post_author">Post Author</label><br>
        <select name="post_author" id="post_author" class="form-control">
            <option value="">Select Author</option>
            <?php
            foreach ($userData as $userDataRow) {
                $name = $userDataRow['first_name'] . " " . $userDataRow['last_name'];
                echo '<option value="' . $userDataRow['user_id'] . '">' . $name . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="post_status">Post Status</label><br>
        <select name="post_status" id="post_status" class="form-control">
            <option value="published">Published</option>
            <option value="draft" selected>Draft</option>
        </select>
    </div>
    <div class="form-group">
        <label for="post_img" class="form-label">Post Image</label>
        <input name="post_img" type="file" id="post_img" class="form-control">
    </div>
    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" name="post_tags" class="form-control" id="post_tags">
    </div>
    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea id="summernote" type="text" name="post_content" class="form-control" id="post_content" cols="30" rows="10"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="create_post" value="Publish Post">
    </div>
</form>