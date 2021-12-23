<?php
include_once '../inc/tool.inc.php';
session_start();
$_SESSION['vcode'] = tool::vcode();
?>