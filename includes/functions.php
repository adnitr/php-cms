<?php
function readTable($connection, $tableName)
{
    $query = "SELECT * FROM $tableName";
    $retarr = [];
    $data = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($data)) {
        array_push($retarr, $row);
    }
    return $retarr;
}

function generateArray($mysqliObject)
{
    $retarr = [];
    while ($row = mysqli_fetch_assoc($mysqliObject)) {
        array_push($retarr, $row);
    }
    return $retarr;
}

function generate1DArray($mysqliObject)
{
    $row = mysqli_fetch_assoc($mysqliObject);
    return $row;
}

function readTableCondition($connection, $tableName, $condition)
{
    $query = "SELECT * FROM $tableName WHERE $condition";
    $retarr = [];
    $data = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($data)) {
        array_push($retarr, $row);
    }
    return $retarr;
}

function countRowsCondition($connection, $tableName, $conditionValArr)
{
    $query = "SELECT * FROM $tableName WHERE ";
    $arrlen = count($conditionValArr);
    $count = 0;
    foreach ($conditionValArr as $key => $val) {
        if ($count !== $arrlen - 1) {
            $query .= "$key = '$val' AND ";
        } else {
            $query .= "$key = '$val'";
        }
        $count++;
    }
    $count = 0;
    $data = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($data)) {
        $count++;
    }
    return $count;
}

function hashPassword($pass)
{
    return password_hash($pass, PASSWORD_BCRYPT, array('cost' => 12));
}

function addCategory($connection, $cat_title)
{
    $query = "INSERT INTO categories(cat_title) VALUE('{$cat_title}')";
    $status = mysqli_query($connection, $query);
    if (!$status) {
        return false;
    }
    return true;
}

function displayAlert($status, $message)
{
    echo '<div class="alert alert-' . $status . ' d-flex align-items-center" role="alert">
    <div>
        ' . $message . '
    </div>
</div>';
}

function deleteRow($connection, $tableName, $id)
{
    $query = "DELETE FROM $tableName WHERE cat_id = $id";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function deletePost($connection, $id)
{
    $query = "DELETE FROM posts WHERE post_id = $id";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function getRowById($connection, $tableName, $idName, $idVal)
{
    $query = "SELECT * FROM $tableName WHERE $idName = '$idVal'";
    $data = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($data);
    return $row;
}

function updateCategory($connection, $catId, $catTitle)
{
    $query = "UPDATE categories SET cat_title = '{$catTitle}' WHERE cat_id = '{$catId}'";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function checkEmpty($catTitle)
{
    return $catTitle === '' || trim($catTitle) === "" || empty($catTitle);
}


function addPost($connection, $postData)
{
    $post_category_id = mysqli_real_escape_string($connection, $postData['post_category_id']);
    $post_author = mysqli_real_escape_string($connection, $postData['post_author']);
    $post_title = mysqli_real_escape_string($connection, $postData['post_title']);
    $post_img = mysqli_real_escape_string($connection, $postData['post_img']);
    $post_content = mysqli_real_escape_string($connection, $postData['post_content']);
    $post_tags = mysqli_real_escape_string($connection, $postData['post_tags']);
    $post_status = mysqli_real_escape_string($connection, $postData['post_status']);

    $post_content = mysqli_real_escape_string($connection, $post_content);

    if (checkEmpty($post_img)) {
        $post_img = 'demoimage.png';
    }

    if (checkEmpty($post_status)) {
        $post_status = 'draft';
    }



    $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_img, post_content, post_tags, post_comment_count, post_status) VALUES ('{$post_category_id}','{$post_title}','{$post_author}',now(),'{$post_img}','{$post_content}','{$post_tags}',0,'{$post_status}')";

    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function createUser($connection, $userData)
{
    $username = $userData['username'];
    $first_name = $userData['first_name'];
    $last_name = $userData['last_name'];
    $email = $userData['email'];
    $password = hashPassword($userData['password']);
    $user_image = $userData['user_image'];
    $user_role = $userData['user_role'];

    if (checkEmpty($user_image)) {
        $user_image = 'download.png';
    }

    if (checkEmpty($user_role)) {
        $user_role = 'subscriber';
    }

    $query = "INSERT INTO users(username, first_name, last_name, user_image, email, user_role, password) VALUES ('{$username}','{$first_name}','{$last_name}','{$user_image}','{$email}','{$user_role}','{$password}')";

    $status = mysqli_query($connection, $query);

    if ($status) {
        return true;
    } else {
        return false;
    }
}

function editPost($connection, $postId, $postData)
{
    $post_category_id = mysqli_real_escape_string($connection, $postData['post_category_id']);
    $post_author = mysqli_real_escape_string($connection, $postData['post_author']);
    $post_title = mysqli_real_escape_string($connection, $postData['post_title']);
    $post_img = mysqli_real_escape_string($connection, $postData['post_img']);
    $post_content = mysqli_real_escape_string($connection, $postData['post_content']);
    $post_tags = mysqli_real_escape_string($connection, $postData['post_tags']);
    $post_status = mysqli_real_escape_string($connection, $postData['post_status']);

    if (checkEmpty($post_img)) {
        $post_img = 'demoimage.png';
    }

    if (checkEmpty($post_status)) {
        $post_status = 'draft';
    }

    $query = "UPDATE posts SET post_category_id = '{$post_category_id}', post_title = '{$post_title}', post_author = '{$post_author}', post_img = '{$post_img}', post_content = '{$post_content}', post_tags = '{$post_tags}', post_status = '{$post_status}' WHERE post_id = {$postId}";

    $status = mysqli_query($connection, $query);

    if ($status) {
        return true;
    } else {
        return false;
    }
}

function editUser($connection, $userData)
{
    $username = $userData['username'];
    $first_name = $userData['first_name'];
    $last_name = $userData['last_name'];
    $email = $userData['email'];
    $password = $userData['password'];
    $user_image = $userData['user_image'];

    if (checkEmpty($user_image)) {
        $user_image = 'download.png';
    }

    if (checkEmpty($password)) {
        $query = "UPDATE users SET username = '{$username}', first_name = '{$first_name}', last_name = '{$last_name}', user_image = '{$user_image}', email = '{$email}' WHERE user_id = {$userData['user_id']}";
    } else {
        $password = hashPassword($password);
        $query = "UPDATE users SET username = '{$username}', first_name = '{$first_name}', last_name = '{$last_name}', user_image = '{$user_image}', email = '{$email}', password = '{$password}' WHERE user_id = {$userData['user_id']}";
    }

    $status = mysqli_query($connection, $query);

    if ($status) {
        return true;
    } else {
        return false;
    }
}

function editProfile($connection, $userData)
{
    $username = $userData['username'];
    $first_name = $userData['first_name'];
    $last_name = $userData['last_name'];
    $email = $userData['email'];
    $password = $userData['password'];

    if (checkEmpty($password)) {
        $query = "UPDATE users SET username = '{$username}', first_name = '{$first_name}', last_name = '{$last_name}', email = '{$email}' WHERE user_id = {$userData['user_id']}";
    } else {
        $password = hashPassword($password);
        $query = "UPDATE users SET username = '{$username}', first_name = '{$first_name}', last_name = '{$last_name}', email = '{$email}', password = '{$password}' WHERE user_id = {$userData['user_id']}";
    }

    $status = mysqli_query($connection, $query);

    if ($status) {
        return true;
    } else {
        return false;
    }
}

function fetchPostsByCategory($connection, $cId)
{
    $query = "SELECT * FROM posts WHERE post_category_id = {$cId}";
    $retarr = [];
    $data = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($data)) {
        array_push($retarr, $row);
    }
    return $retarr;
}


function substring($string)
{
    $len = strlen($string);
    if ($len > 300) {
        $string = substr($string, 0, 300) . '...';
    }
    return $string;
}

function substradv($string, $len)
{
    $len = strlen($string);
    if ($len > $len) {
        $string = substr($string, 0, $len) . '...';
    }
    return $string;
}

function increaseCommentCount($connection, $postId)
{
    $query = "UPDATE posts SET post_comment_count = post_comment_count + 1 WHERE post_id = {$postId}";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function decrementCommentCount($connection, $postId)
{
    $query = "UPDATE posts SET post_comment_count = post_comment_count - 1 WHERE post_id = {$postId}";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

// function countComments($connection, $postId)
// {
//     $query = "SELECT * FROM comments WHERE comment_post_id = $postId";
//     $status = mysqli_query($connection, $query);
//     $noOfComments = mysqli_num_rows($status);
//     return $noOfComments;
// }

function addComment($connection, $postId, $commentAuthor, $commentEmail, $commentContent, $commentAuthorId)
{
    $commentContent = mysqli_real_escape_string($connection, $commentContent);
    $query = "INSERT INTO comments(comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date, comment_author_id) VALUES ('{$postId}', '{$commentAuthor}', '{$commentEmail}', '{$commentContent}', 'unapproaved', now(), '{$commentAuthorId}')";

    $status = mysqli_query($connection, $query);
    increaseCommentCount($connection, $postId);
    if ($status) return true;
    else return false;
}

function deleteComment($connection, $deleteId)
{
    $query = "DELETE FROM comments WHERE comment_id = {$deleteId}";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else return false;
}

function deleteUser($connection, $deleteId)
{
    $query = "DELETE FROM users WHERE user_id = {$deleteId}";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else return false;
}

function approaveComment($connection, $commentId)
{
    $query = "UPDATE comments SET comment_status = 'approaved' WHERE comment_id = {$commentId}";
    $status = mysqli_query($connection, $query);
    if ($status) return true;
    else return false;
}

function unapproaveComment($connection, $commentId)
{
    $query = "UPDATE comments SET comment_status = 'unapproaved' WHERE comment_id = {$commentId}";
    $status = mysqli_query($connection, $query);
    if ($status) return true;
    else return false;
}

function viewComments($connection, $postId)
{
    $query = "SELECT * FROM comments WHERE comment_post_id = {$postId} AND comment_status = 'approaved' ORDER BY comment_id DESC";
    $retarr = [];
    $data = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($data)) {
        array_push($retarr, $row);
    }
    return $retarr;
}

function countRowsInTable($connection, $tableName)
{
    $query = "SELECT COUNT(*) as count_rowws FROM $tableName";
    $data = mysqli_query($connection, $query);
    $data = mysqli_fetch_assoc($data);
    return $data['count_rowws'];
}

function countRowsByColVal($connection, $tableName, $colName, $colVal)
{
    $tableName = mysqli_real_escape_string($connection, $tableName);
    $colName = mysqli_real_escape_string($connection, $colName);
    $colVal = mysqli_real_escape_string($connection, $colVal);
    $query = "SELECT * FROM $tableName WHERE $colName = '$colVal'";
    $data = mysqli_query($connection, $query);
    $count = 0;
    while ($row = mysqli_fetch_assoc($data)) {
        $count++;
    }
    return $count;
}

function countRowsByCondition($connection, $tableName, $condition)
{
    $tableName = mysqli_real_escape_string($connection, $tableName);
    $query = "SELECT * FROM $tableName WHERE $condition";
    $data = mysqli_query($connection, $query);
    $count = 0;
    while ($row = mysqli_fetch_assoc($data)) {
        $count++;
    }
    return $count;
}

function modifyAField($connection, $tableName, $idName, $idVal, $fieldName, $newFieldVal)
{
    $fieldName = mysqli_real_escape_string($connection, $fieldName);
    $newFieldVal = mysqli_real_escape_string($connection, $newFieldVal);

    $query = "UPDATE $tableName SET $fieldName = '$newFieldVal' WHERE $idName = $idVal";
    $status = mysqli_query($connection, $query);
    if ($status) return true;
    else return false;
}

function clonePost($connection, $postId)
{
    $row = getRowById($connection, "posts", "post_id", $postId);

    $row['post_category_id'] = mysqli_real_escape_string($connection, $row['post_category_id']);
    $row['post_title'] = mysqli_real_escape_string($connection, $row['post_title']);
    $row['post_author'] = mysqli_real_escape_string($connection, $row['post_author']);
    $row['post_content'] = mysqli_real_escape_string($connection, $row['post_content']);
    $row['post_tags'] = mysqli_real_escape_string($connection, $row['post_tags']);

    $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date, post_img, post_content, post_tags, post_comment_count, post_status) VALUES ('{$row['post_category_id']}','{$row['post_title']}','{$row['post_author']}',now(),'{$row['post_img']}','{$row['post_content']}','{$row['post_tags']}',0,'{$row['post_status']}')";

    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function incPostView($connection, $postId)
{
    $query = "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = {$postId}";
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function insertIntoTable($connection, $tableName, $colsInOrder, $valuesInOrder)
{
    $query = "INSERT INTO $tableName(";
    $numCols = count($colsInOrder);
    for ($i = 0; $i < $numCols; $i++) {
        $query .= $colsInOrder[$i];
        if ($i !== $numCols - 1) {
            $query .= ",";
        } else {
            $query .= ")";
        }
    }
    $query .= "VALUES(";
    for ($i = 0; $i < $numCols; $i++) {
        $query .= "'{$valuesInOrder[$i]}'";
        if ($i !== $numCols - 1) {
            $query .= ",";
        } else {
            $query .= ")";
        }
    }
    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function updateFieldByColVal($connection, $tableName, $colName, $colVal, $colsInOrder, $colValsInOrder)
{
    $query = "UPDATE $tableName SET ";
    $numCols = count($colsInOrder);
    for ($i = 0; $i < $numCols; $i++) {

        $query .= $colsInOrder[$i];
        $query .= " = ";
        $query .= "'{$colValsInOrder[$i]}'";
        if ($i !== $numCols - 1) {
            $query .= ",";
        }
        $query .= " ";
    }
    $query .= "WHERE $colName = '{$colVal}'";

    $status = mysqli_query($connection, $query);
    if ($status) {
        return true;
    } else {
        return false;
    }
}

function countUsersOnline()
{
    if (isset($_GET['onlineusers'])) {
        global $connection;
        if (!$connection) {
            session_start();
            include "../includes/db.php";
        }

        $session = session_id();
        $time = time();

        $countOnline = countRowsByColVal($connection, "users_online", "session", $session);
        if (!$countOnline) {
            insertIntoTable($connection, "users_online", ["session", "time"], [$session, $time]);
        } else {
            updateFieldByColVal($connection, "users_online", "session", $session, ["time"], [$time]);
        }
        $timeThen = time() - 60;
        $noOfUsersOnline = countRowsByCondition($connection, "users_online", "time > $timeThen");
        echo $noOfUsersOnline;
        return $noOfUsersOnline;
    }
}

countUsersOnline();

function shouldShowPost($post_status, $user_role)
{
    if ($user_role === 'admin') {
        return true;
    } else {
        if ($post_status === 'published') {
            return true;
        } else {
            return false;
        }
    }
}

function isDuplicate($connection, $tableName, $fieldName, $valToCheck)
{

    $query = "SELECT * FROM $tableName WHERE $fieldName = '$valToCheck'";
    $result = mysqli_query($connection, $query);
    $num = mysqli_num_rows($result);
    if ($num) {
        return true;
    } else {
        return false;
    }
}

function checkAdmin()
{
    global $_SESSION;
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
        return 'admin';
    } else if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'subscriber') {
        return 'subscriber';
    } else {
        return null;
    }
}

function isAdmin()
{
    global $_SESSION;
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');
}

function redirect($location)
{
    header("Location: " . $location);
    exit;
}

function isGivenMethod($method = null)
{
    if ($_SERVER['REQUEST_METHOD'] === strtoupper($method)) {
        return true;
    }
    return false;
}

function isLoggedIn()
{
    global $_SESSION;
    if (isset($_SESSION['user_id'])) return true;
    return false;
}


function isLiked($connection, $postId)
{
    $is_logged_in = isLoggedIn();
    if ($is_logged_in) {
        //find if the user has liked the post
        global $_SESSION;
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM likes WHERE user_id = '$user_id' AND post_id = '$postId'";
        $data = mysqli_query($connection, $query);
        $countRows = mysqli_num_rows($data);
        if ($countRows) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function getPostLikes($post_id)
{
    global $connection;
    $query = "SELECT post_likes FROM posts WHERE post_id = '$post_id'";
    $data = mysqli_query($connection, $query);
    $data = mysqli_fetch_assoc($data);
    return $data['post_likes'];
}

function isAuthorOfPost($post_id)
{
    if (isset($_SESSION['user_role'])) {
        if ($_SESSION['user_role']) {
            global $connection;
            $user_id = $_SESSION['user_id'];
            $query = "SELECT * FROM posts WHERE post_id = '$post_id'";
            $data = mysqli_query($connection, $query);
            $row = mysqli_fetch_assoc($data);
            if ($row['post_author'] == $user_id) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function isAuthorPost($postId)
{
    global $connection;
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM posts WHERE post_id = '$postId' AND post_author = '$user_id'";
    $result = mysqli_query($connection, $query);
    $count = mysqli_num_rows($result);
    if ($count) return true;
    else return false;
}

function isCommentToAuthorPost($commentId)
{
    global $connection;
    $query = "SELECT * FROM comments WHERE comment_id = $commentId";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $postId = $row['comment_post_id'];
    return isAuthorPost($postId);
}

function countCommentAuthorPosts()
{
    global $connection;
    $count = 0;
    $query = "SELECT * FROM comments";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        if (isAuthorPost($row['comment_post_id'])) {
            $count++;
        }
    }
    return $count;
}

function countAuthorPosts()
{
    global $connection;
    $count = 0;
    $query = "SELECT * FROM posts";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        if (isAuthorPost($row['post_id'])) {
            $count++;
        }
    }
    return $count;
}

function countAuthorDraftPosts()
{
    global $connection;
    $count = 0;
    $query = "SELECT * FROM posts WHERE post_status = 'draft'";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        if (isAuthorPost($row['post_id'])) {
            $count++;
        }
    }
    return $count;
}

function countAuthorUnapprComments()
{
    global $connection;
    $count = 0;

    $query = "SELECT * FROM comments WHERE comment_status = 'unapproaved'";
    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        if (isAuthorPost($row['comment_post_id'])) {
            $count++;
        }
    }
    return $count;
}

function isAlreadyCommented($postId)
{
    global $connection;
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM comments WHERE comment_post_id = '$postId' AND comment_author_id = '$user_id'";
    $result = mysqli_query($connection, $query);
    $numRows = mysqli_num_rows($result);
    if ($numRows) {
        return true;
    } else {
        return false;
    }
}

function getMyComments()
{
    global $connection;

    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM comments WHERE comment_author_id = '$user_id'";
    $result = mysqli_query($connection, $query);

    return generateArray($result);
}

function isMyComment($commentId)
{
    global $connection;

    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM comments WHERE comment_author_id = '$user_id' AND comment_id = '$commentId'";
    $result = mysqli_query($connection, $query);
    $numRows = mysqli_num_rows($result);

    if ($numRows) {
        return true;
    } else {
        return false;
    }
}
