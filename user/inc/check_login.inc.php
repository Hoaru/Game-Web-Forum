<?php
if (empty($_POST['name'])) {
    tool::skip('login.php', '用户名不能为空！', 'error');
}
if (mb_strlen($_POST['name']) > 32) {
    tool::skip('login.php', '用户名不能超过16个字符！', 'error');
}
if (empty($_POST['pw'])) {
    tool::skip('login.php', '密码不能为空！', 'error');
}
if (empty($_POST['vcode'])) {
    tool::skip('login.php', '请输入验证码', 'error');
}
if (strtolower($_POST['vcode']) != strtolower($_SESSION['vcode'])) {
    tool::skip('login.php', '验证码输入错误', 'error');
}
?>