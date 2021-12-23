<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '登录';
$template['css'] = array('style/public.css', 'style/register.css');
$member_id = tool::is_login();
if (!$member_id) {
    tool::skip('index.php', '您还没有登录', 'error');
}

setcookie('la[name]', time() - 1);
setcookie('la[pw]', time() - 1);
tool::skip('index.php', '退出成功', 'ok');
?>