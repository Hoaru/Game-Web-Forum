<?php
if (empty($_POST['name'])) {
    tool::skip('login.php', '用户名不能为空', 'error');
}

if (strtolower($_POST['vcode']) != strtolower($_SESSION['vcode'])) {
    tool::skip('login.php', '验证码错误', 'error');
}
?>