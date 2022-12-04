<?php
session_start();
$_SESSION['username'] = null;
$_SESSION['user_id'] = null;
$_SESSION['first_name'] = null;
$_SESSION['last_name'] = null;
$_SESSION['user_role'] = null;
$_SESSION['email'] = null;
header("Location: ../index.php");
