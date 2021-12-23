<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
include_once 'inc/is_manager_login.inc.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('manager.php', 'id参数错误', 'error');
}
ConnectMysqli::connect();
$query = "delete from la_manager where id = {$_GET['id']}";
$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if ($result) {
    tool::skip('manager.php', '删除成功！', 'ok');
} else {
    tool::skip('manager.php', '删除失败', 'error');
}
?>
