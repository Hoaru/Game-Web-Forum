<?php
if (empty($_POST['manager_name'])) {
    tool::skip('manager_add.php', '用户名不能为空', 'error');
}
if (mb_strlen($_POST['pw']) < 6) {
    tool::skip('manager_add.php', '密码不能小于6位', 'error');
}
ConnectMysqli::connect();
$_POST['manager_name'] = ConnectMysqli::escape($_POST['manager_name']);

$query = "select * from la_manager where name = '{$_POST['manager_name']}'";
$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if (mysqli_num_rows($result)) {
    tool::skip('manager_add.php', '用户名已存在', 'error');
}
if ($_POST['status'] != "0" && $_POST['status'] != "1") {
    $_POST['status'] = "1";
}
?>