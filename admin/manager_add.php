<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '添加管理员';
$template['css'] = array('style/public.css');
include_once 'inc/is_manager_login.inc.php';
if (isset($_POST['submit'])) {

    include_once 'inc/check_manager.inc.php';

    ConnectMysqli::connect();
    $query = "insert into la_manager(name, pw, create_time, status) 
    values('{$_POST['manager_name']}', md5('{$_POST['pw']}'), now(), '{$_POST['status']}')";
    $result = ConnectMysqli::execute($query);
    ConnectMysqli::close();
    if ($result) {
        tool::skip('manager_add.php', '添加成功', 'ok');
    } else {
        tool::skip('manager_add.php', '添加失败', 'error');
    }
}

?>

<?php include_once 'inc/header.inc.php' ?>
<div id="main">
    <div class="title" style="margin-bottom:20px">添加管理员</div>
    <form method="post">
        <table class="au">
            <tr>
                <td>管理员名称</td>
                <td><input name="manager_name" type="text" /></td>
            </tr>
            <tr>
                <td>密码</td>
                <td><input name="pw" type="password" /></td>
            </tr>
            <tr>
                <td>等级</td>
                <td>
                    <select name="status">
                        <option value="1">普通管理员</option>
                        <option value="0">超级管理员</option>
                    </select>
                </td>
            </tr>
        </table>
        <input style="margin-top:20px; cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
    </form>
</div>
<?php include_once 'inc/footer.inc.php'; ?>