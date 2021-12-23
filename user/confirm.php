<?php
include_once '../inc/config.inc.php';
if(!isset($_GET['message']) || !isset($_GET['delete_url']) || !isset($_GET['return_url'])) {
    exit();
}
$_GET['message'] = htmlspecialchars($_GET['message']);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>确认页面</title>
<meta name="keywords" content="后台界面" />
<meta name="description" content="后台界面" />
<link rel="stylesheet" type="text/css" href="style/remind.css" />
</head>
<body>
<div class="notice">
    <span class="pic ask"></span> <?php echo $_GET['message'];?>
    <a style = "color:red"  href="<?php echo $_GET['delete_url'];?>">确定</a> | 
    <a style = "color:#666" href="<?php echo $_GET['return_url'];?>">取消</a>
</div>
</body>
</html>