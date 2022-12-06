<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>
            <th>In Response to</th>
            <th>Date</th>
            <th>Approve</th>
            <th>Unapprove</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($commentData as $commentDataRow) {
            if (isAdmin() || isAuthorPost($commentDataRow['comment_post_id'])) {
                $commentPost = getRowById($connection, "posts", "post_id", $commentDataRow['comment_post_id']);
        ?>
                <tr>
                    <td><?php echo $commentDataRow['comment_id']; ?></td>
                    <td><?php echo $commentDataRow['comment_author']; ?></td>
                    <td><?php echo substradv($commentDataRow['comment_content'], 100); ?></td>
                    <td><?php echo $commentDataRow['comment_email']; ?></td>
                    <td><?php echo $commentDataRow['comment_status']; ?></td>
                    <td> <a href="../post.php?p-id=<?php echo $commentDataRow['comment_post_id']; ?>"><?php echo $commentPost['post_title']; ?></a></td>
                    <td><?php echo $commentDataRow['comment_date']; ?></td>
                    <?php
                    if ($pId == 0) {
                    ?>
                        <td><a href="?approave=<?php echo $commentDataRow['comment_id']; ?>">Aapprove</a></td>
                        <td><a href="?unapproave=<?php echo $commentDataRow['comment_id']; ?>">Unapprove</a></td>
                    <?php } else { ?>
                        <td><a href="?p-id=<?php echo $pId; ?>&approave=<?php echo $commentDataRow['comment_id']; ?>">Aapprove</a></td>
                        <td><a href="?p-id=<?php echo $pId; ?>&unapproave=<?php echo $commentDataRow['comment_id']; ?>">Unapprove</a></td>
                    <?php } ?>
                    <?php
                    if ($pId == 0) {
                    ?>
                        <td><a href="?delete=<?php echo $commentDataRow['comment_id']; ?>">Delete</a></td>
                    <?php } else { ?>
                        <td><a href="?p-id=<?php echo $pId; ?>&delete=<?php echo $commentDataRow['comment_id']; ?>">Delete</a></td>
                    <?php } ?>
                </tr>
        <?php }
        } ?>
    </tbody>
</table>


<h1>Your comments</h1>
<?php $myComments = getMyComments(); ?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>
            <th>In Response to</th>
            <th>Date</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($myComments as $myCommentRow) {
            $commentPost = getRowById($connection, "posts", "post_id", $myCommentRow['comment_post_id']);
        ?>
            <tr>
                <td><?php echo $myCommentRow['comment_id']; ?></td>
                <td><?php echo $myCommentRow['comment_author']; ?></td>
                <td><?php echo substradv($myCommentRow['comment_content'], 100); ?></td>
                <td><?php echo $myCommentRow['comment_email']; ?></td>
                <td><?php echo $myCommentRow['comment_status']; ?></td>
                <td> <a href="../post.php?p-id=<?php echo $myCommentRow['comment_post_id']; ?>"><?php echo $commentPost['post_title']; ?></a></td>
                <td><?php echo $myCommentRow['comment_date']; ?></td>
                <?php
                if ($pId == 0) {
                ?>
                    <td><a href="?delete=<?php echo $myCommentRow['comment_id']; ?>">Delete</a></td>
                <?php } else { ?>
                    <td><a href="?p-id=<?php echo $pId; ?>&delete=<?php echo $myCommentRow['comment_id']; ?>">Delete</a></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
</table>