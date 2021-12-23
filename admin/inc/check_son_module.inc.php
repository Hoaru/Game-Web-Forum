<?php
if (!is_numeric($_POST['father_module_id'])) {
    tool::skip('son_module_add.php', '所属父板块不能为空', 'error');
}

$query = "select * from la_father_module where id={$_POST['father_module_id']}";
ConnectMysqli::connect();
$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if (!mysqli_num_rows($result)) {
    tool::skip('son_module_add.php', '所属父板块不能为空', 'error');
}
if (empty($_POST['module_name'])) {
    tool::skip('son_module_add.php', '子板块名称不得为空', 'error');
}
ConnectMysqli::connect();
$_POST['module_name'] = ConnectMysqli::escape($_POST['module_name']);
$_POST['info'] = ConnectMysqli::escape($_POST['info']);
switch ($check_flag) {
    case 'add':
        $query = "select * from la_son_module where module_name = '{$_POST['module_name']}' and father_module_id = '{$_POST['father_module_id']}'";
        break;
    case 'modify':
        $query = "select * from la_son_module where module_name = '{$_POST['module_name']}' and id != '{$_GET['id']}' and father_module_id = '{$_POST['father_module_id']}'";
        break;
    default:
        tool::skip('son_module.php', '参数错误', 'error');
}
$result = ConnectMysqli::execute($query);
$data = mysqli_fetch_assoc($result);
ConnectMysqli::close();
if (mysqli_num_rows($result)) {
    tool::skip('son_module.php', '此板块已存在', 'error');
}

if (!is_numeric($_POST['sort'])) {
    tool::skip('son_module_add.php', '排序只能是数字', 'error');
}

