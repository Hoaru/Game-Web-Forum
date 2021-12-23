<?php
if (empty($_POST['module_name'])) {
    tool::skip('father_module_add.php', '板块名称不得为空', 'error');
}
if (!is_numeric($_POST['sort'])) {
    tool::skip('father_module_add.php', '排序只能是数字', 'error');
}
ConnectMysqli::connect();
$_POST['module_name'] = ConnectMysqli::escape($_POST['module_name']);

switch ($check_flag) {
    case 'add':
        $query = "select * from la_father_module where module_name = '{$_POST['module_name']}'";
        break;
    case 'modify':
        $query = "select * from la_father_module where module_name = '{$_POST['module_name']}' and id != '{$_GET['id']}'";
        break;
    default:
    tool::skip('father_module.php', '参数错误', 'error');
}

$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if (mysqli_num_rows($result)) {
    tool::skip('father_module.php', '此板块已存在', 'error');
}
?>