<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$template['title'] = '编辑';
$template['css'] = array('style/public.css');
include_once 'inc/is_manager_login.inc.php';
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('son_module.php', '此板块不存在', 'error');
}

ConnectMysqli::connect();
$query = "select * from la_son_module where id={$_GET['id']}";
$result = ConnectMysqli::execute($query);
ConnectMysqli::close();
if ($result) {
    $data = mysqli_fetch_assoc($result);
} else {
    tool::skip('son_module.php', '此板块不存在', 'error');
}


if (isset($_POST['modify'])) {
    $check_flag = 'modify';
    include_once 'inc/check_son_module.inc.php';

    $query = "update la_son_module set 
    father_module_id = '{$_POST['father_module_id']}', 
    module_name = '{$_POST['module_name']}', 
    info = '{$_POST['info']}', 
    member_id = '{$_POST['member_id']}', 
    sort = '{$_POST['sort']}' where id = '{$_GET['id']}'";

    ConnectMysqli::connect();
    $result = ConnectMysqli::execute($query);
    ConnectMysqli::close();
    if ($result) {
        tool::skip('son_module.php', '修改成功', 'ok');
    } else {
        tool::skip('son_module.php', '修改失败', 'error');
    }
}
?>


<?php include 'inc/header.inc.php'; ?>

<div id="main">
    <div class="title" style="margin-bottom:20px">编辑子版块-<?php echo $data['module_name'] ?></div>
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
                            if ($data['father_module_id'] == $data_father['id']) {
                                echo "<option selected='selected' value='{$data_father['id']}'>{$data_father['module_name']}</option>";
                            } else {
                                echo "<option value='{$data_father['id']}'>{$data_father['module_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>版块名称</td>
                <td><input name="module_name" type="text" value="<?php echo $data['module_name'] ?>"/></td>
            </tr>
            <tr>
                <td>板块简介</td>
                <td>
                    <textarea name="info"><?php echo $data['info'] ?></textarea>
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
                <td><input name="sort" type="text" value="<?php echo $data['sort'] ?>"/></td>
            </tr>
        </table>
        <input style="margin-top:20px; cursor:pointer;" class="btn" type="submit" name="modify" value="修改" />
    </form>
</div>

<?php include 'inc/footer.inc.php'; ?>