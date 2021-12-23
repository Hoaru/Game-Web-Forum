<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '父板块列表页';
$template['css'] = array('style/public.css');
include_once 'inc/is_manager_login.inc.php';

if (isset($_POST['modify'])) {
    ConnectMysqli::connect();
    foreach ($_POST['sort'] as $key => $val) {
        if (!is_numeric($val) || !is_numeric($key)) {
            tool::skip('father_module.php', '排序参数不合法', 'error');
        }
        $query[] = "update la_father_module set sort = '{$val}' 
        where id = '{$key}'";
    }
    if (ConnectMysqli::execute_multi($query, $error)) {
        tool::skip('father_module.php', '排序修改成功', 'ok');
    } else {
        tool::skip('father_module.php', $error, 'error');
    }
    ConnectMysqli::close();
}

?>

<?php include 'inc/header.inc.php'; ?>

<div id="main">
    <div class="title">父板块列表</div>
    <form method="post">
        <table class="list">
            <tr>
                <th>排序</th>
                <th>版块名称</th>
                <th>操作</th>
            </tr>
            <?php
            ConnectMysqli::connect();
            $query = 'select * from la_father_module order by sort';
            $result = ConnectMysqli::execute($query);
            while ($data = mysqli_fetch_assoc($result)) {
                $delete_url = urlencode("father_module_delete.php?id={$data['id']}");
                $return_url = urlencode($_SERVER['REQUEST_URI']);
                $message = "你确定要删除父板块 {$data['module_name']} 吗？";
                $confirm_url = "confirm.php?delete_url={$delete_url}&return_url={$return_url}&message={$message}";

                $html = <<<S
            <tr>
                <td><input class="sort" type="text" name="sort[{$data['id']}]" value="{$data['sort']}"/></td>
                <td>{$data['module_name']} [id:{$data['id']}]</td>
                <td>
                    <a href="../user/list_father.php?id={$data['id']}">[访问]</a>&nbsp;&nbsp;
                    <a href="father_module_update.php?id={$data['id']}">[编辑]</a>&nbsp;&nbsp;
                    <a href="{$confirm_url}">[删除]</a>
                </td>
            </tr>         
            S;
                echo $html;
            }
            ConnectMysqli::close();
            ?>
        </table>
        <input style="margin-top:20px; cursor:pointer;" class="btn" type="submit" name="modify" value="排序" />
    </form>
</div>

<?php include 'inc/footer.inc.php'; ?>