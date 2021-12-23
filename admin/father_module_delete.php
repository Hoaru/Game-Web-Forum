<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
include_once 'inc/is_manager_login.inc.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('father_module.php', 'id参数传递失败', 'error');
}
ConnectMysqli::connect();

$query = "select * from la_son_module where father_module_id = {$_GET['id']}";
$result = ConnectMysqli::execute($query);
if (mysqli_num_rows($result)) {
    tool::skip('father_module.php', '该父板块下面存在子版块', 'error');
}

$query = "delete from la_father_module where id = {$_GET['id']}";
$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if ($result) {
    tool::skip('father_module.php', '删除成功！', 'ok');
} else {
    tool::skip('father_module.php', '删除失败', 'error');
}
?>
