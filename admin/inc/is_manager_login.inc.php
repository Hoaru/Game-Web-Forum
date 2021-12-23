<?php
include_once '../inc/tool.inc.php';
if (!tool::is_manager_login()) {
    tool::skip('login.php', '请登录', 'error');
}
if (
    basename($_SERVER['SCRIPT_NAME']) == 'manager_delete.php' ||
    basename($_SERVER['SCRIPT_NAME']) == 'manager_add.php'
) {
    if ($_SESSION['manager']['status'] != '0') {
        if (!isset($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = 'index.php';
        }
        tool::skip($_SERVER['HTTP_REFERER'], '权限不足', 'error');
    }
}
?>