<form action="categories.php" method="POST">
    <div class="form-group">
        <label for="cat_title">Add Category</label>
        <input type="text" class="form-control" name="cat_title" id="cat_title">
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" value="Add Category" name="submit">
    </div>
</form>
<?php
if (isset($_POST['submit']) && $_SESSION['user_role'] === 'admin' && isset($_POST['cat_title'])) {
    $catTitle = $_POST['cat_title'];
    if (checkEmpty($catTitle)) {
        displayAlert('danger', "Category field can't be empty");
    } else {
        $status = addCategory($connection, $catTitle);
        if (!$status) {
            displayAlert('danger', 'Something went wrong!');
        } else {
            displayAlert('success', 'Category added successfully!');
            $catData = readTable($connection, "categories");
        }
    }
}
?>