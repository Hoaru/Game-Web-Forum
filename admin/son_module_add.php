<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$template['title'] = '子版块添加';
$template['css'] = array('style/public.css');
include_once 'inc/is_manager_login.inc.php';
if (isset($_POST['submit'])) {

    $check_flag = 'add';
    include_once 'inc/check_son_module.inc.php';

    $query = "insert into la_son_module(father_module_id, module_name, info, member_id, sort) 
    values('{$_POST['father_module_id']}', '{$_POST['module_name']}', '{$_POST['info']}', '{$_POST['member_id']}', '{$_POST['sort']}')";
    ConnectMysqli::connect();
    $result = ConnectMysqli::execute($query);
    ConnectMysqli::close();
    if ($result) {
        tool::skip('son_module.php', '添加成功', 'ok');
    } else {
        tool::skip('son_module_add.php', '添加失败', 'error');
    }
}
?>


<?php include 'inc/header.inc.php'; ?>

<div id="main">
    <div class="title" style="margin-bottom:20px">添加子版块</div>
    <form method="post">
        <table class="au">
            <tr>
                <td>所属父板块</td>
                <td>
                    <select name="father_module_id">
                        <option value="0">==========请选择一个父板块==========</option>
                        <?php
                        ConnectMysqli::connect();
                        $query = "select * from la_father_module";
                        $result_father = ConnectMysqli::execute($query);
                        ConnectMysqli::close();
                        while ($data_father = mysqli_fetch_assoc($result_father)) {
                            echo "<option value='{$data_father['id']}'>{$data_father['module_name']}</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" type="text" /></td>
            </tr>
            <tr>
                <td>板块简介</td>
                <td>
                    <textarea name="info"></textarea>
                </td>
            </tr>
            <tr>
                <td>版主</td>
                <td>
                    <select name="member_id">
                        <option value="0">========请选择一个会员作为版主========</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" type="text" value="0"/></td>
            </tr>
        </table>
        <input style="margin-top:20px; cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
    </form>
</div>

<?php include 'inc/footer.inc.php'; ?>