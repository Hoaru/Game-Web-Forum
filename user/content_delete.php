<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$member_id = tool::is_login();
$is_manager_login = tool::is_manager_login();
if (!$member_id && !$is_manager_login) {
    tool::skip('login.php', '请登录之后再做删除操作', 'error');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('index.php', 'id参数错误', 'error');
}
ConnectMysqli::connect();
$query = "select member_id from la_content where id = {$_GET['id']}";
$result = ConnectMysqli::execute($query);
if (!mysqli_num_rows($result)) {
    tool::skip('index.php', '操作错误', 'error');
} else {
    $data = mysqli_fetch_assoc($result);
    if ($data['member_id'] == $member_id || $is_manager_login) {
        $query = "delete from la_content where id = {$_GET['id']}";
        $result = ConnectMysqli::execute_bool($query);
        if (isset($_GET['return_url'])) {
            $return_url = $_GET['return_url'];
        } else {
            $return_url = "member.php?id={$member_id}";
        }
        if ($result) {
            tool::skip($return_url, '删除成功', 'ok');
        } else {
            tool::skip($return_url, '删除失败', 'error');
        }
    }
}
ConnectMysqli::connect();
?>