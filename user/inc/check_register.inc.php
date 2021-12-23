<?php
if (empty($_POST['name'])) {
    tool::skip('register.php', '用户名不能为空！', 'error');
}
if (mb_strlen($_POST['name']) > 32) {
    tool::skip('register.php', '用户名不能超过16个字符！', 'error');
}
if (mb_strlen($_POST['pw']) < 6) {
    tool::skip('register.php', '密码不能少于6位！', 'error');
}
if ($_POST['pw'] != $_POST['confirm_pw']) {
    tool::skip('register.php', '两次密码输入不一致！', 'error');
}
if (empty($_POST['vcode'])) {
    tool::skip('register.php', '请输入验证码', 'error');
}
if (strtolower($_POST['vcode']) != strtolower($_SESSION['vcode'])) {
    tool::skip('register.php', '验证码输入错误', 'error');
}


ConnectMysqli::connect();
$_POST['name'] = ConnectMysqli::escape($_POST['name']);
$query = "select * from la_member where name = '{$_POST['name']}'";
$result = ConnectMysqli::execute($query);
if (mysqli_num_rows($result)) {
    tool::skip('register.php', '用户名已存在！', 'error');
}
ConnectMysqli::close();
?>