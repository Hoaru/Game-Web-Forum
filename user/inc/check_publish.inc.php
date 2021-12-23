<?php
if (empty($_POST['module_id'])) {
    tool::skip('publish.php', '所属板块id不合法！', 'error');
}

ConnectMysqli::connect();
$query = "select * from la_son_module where 
id = {$_POST['module_id']}";
$result = ConnectMysqli::execute($query);
if (!mysqli_num_rows($result)) {
    tool::skip('publish.php', '请选择一个板块！', 'error');
}

if (empty($_POST['title'])) {
    tool::skip('publish.php', '标题不能为空！', 'error');
}

if (mb_strlen($_POST['title']) > 255) {
    tool::skip('publish.php', '标题不能超过255个字符！', 'error');
}

?>