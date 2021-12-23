<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
include_once 'inc/is_manager_login.inc.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('son_module.php', 'id参数传递失败', 'error');
}
ConnectMysqli::connect();
$query = "delete from la_son_module where id = {$_GET['id']}";
$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if ($result) {
    tool::skip('son_module.php', '删除成功！', 'ok');
} else {
    tool::skip('son_module.php', '删除失败', 'error');
}
?>
