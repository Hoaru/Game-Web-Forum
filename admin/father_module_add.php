<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';
$template['title'] = '父板块添加页';
$template['css'] = array('style/public.css');
include_once 'inc/is_manager_login.inc.php';
if (isset($_POST['submit'])) {

    $check_flag = 'add';
    include_once 'inc/check_father_module.inc.php';

    ConnectMysqli::connect();
    $query = "insert into la_father_module(module_name, sort) 
    values('{$_POST['module_name']}', '{$_POST['sort']}')";
    $result = ConnectMysqli::execute($query);
    ConnectMysqli::close();
    if ($result) {
        tool::skip('father_module.php', '添加成功', 'ok');
    } else {
        tool::skip('father_module_add.php', '添加失败', 'error');
    }
}

?>

<?php include_once 'inc/header.inc.php' ?>
<div id="main">
    <div class="title" style="margin-bottom:20px">添加父板块</div>
    <form method="post">
        <table class="au">
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" type="text" /></td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" type="text" value="0" /></td>
            </tr>
        </table>
        <input style="margin-top:20px; cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
    </form>
</div>
<?php include_once 'inc/footer.inc.php'; ?>