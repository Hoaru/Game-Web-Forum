<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$template['title'] = '编辑';
$template['css'] = array('style/public.css');
include_once 'inc/is_manager_login.inc.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('father_module.php', '此板块不存在', 'error');
}
ConnectMysqli::connect();
$query = "select * from la_father_module where id={$_GET['id']}";
$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if ($result) {
    $data = mysqli_fetch_assoc($result);
} else {
    tool::skip('father_module.php', '此板块不存在', 'error');
}

if (isset($_POST['modify'])) {
    $check_flag = 'modify';
    include_once 'inc/check_father_module.inc.php';

    $query = "update la_father_module set module_name = '{$_POST['module_name']}', sort = '{$_POST['sort']}' where id = '{$_GET['id']}'";
    ConnectMysqli::connect();
    $result = ConnectMysqli::execute($query);
    ConnectMysqli::close();
    if ($result) {
        tool::skip('father_module.php', '修改成功', 'ok');
    } else {
        tool::skip('father_module.php', '修改失败', 'error');
    }
}

?>

<?php include 'inc/header.inc.php'; ?>

<div id="main">
    <div class="title" style="margin-bottom:20px">编辑父板块-<?php echo $data['module_name'] ?></div>
    <form method="post">
        <table class="au">
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" type="text" value="<?php echo $data['module_name'] ?>" /></td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" type="text" value="<?php echo $data['sort'] ?>" /></td>
            </tr>
        </table>
        <input style="margin-top:20px; cursor:pointer;" class="btn" type="submit" name="modify" value="修改" />
    </form>
</div>

<?php include 'inc/footer.inc.php'; ?>