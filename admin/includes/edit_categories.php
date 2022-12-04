<?php
if (isset($editSt['id'])) {
    $editedTitle = $editSt['title'];
?>
    <form action="categories.php" method="POST">

        <div class="form-group">
            <label for="cat_title_edit">Edit Category</label>
            <input type="text" class="form-control" name="cat_title_edit" id="cat_title_edit" value="<?php echo $editedTitle ?>">
            <input type="text" class="form-control" name="cat_edit_id" id="cat_edit_id" style="display: none;" value=<?php echo $editSt['id']; ?>>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Edit Category" name="edit">
        </div>
    </form>
<?php } ?>
<?php
if (isset($_POST['edit']) && $_SESSION['user_role'] === 'admin' && isset($_POST['cat_title_edit']) && isset($_POST['cat_edit_id'])) {
    $catTitleEdit = $_POST['cat_title_edit'];
    if (checkEmpty($catTitleEdit)) {
        displayAlert('danger', "Category field can't be empty");
    } else {
        $statusEdit = updateCategory($connection, $_POST['cat_edit_id'], $catTitleEdit);
        if (!$statusEdit) {
            displayAlert('danger', 'Something went wrong!');
        } else {
            displayAlert('success', 'Category edited successfully!');
            $catData = readTable($connection, "categories");
        }
    }
}
?>