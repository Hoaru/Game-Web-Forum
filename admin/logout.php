<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');
header('Location:login.php');
?>