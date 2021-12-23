<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '管理员列表';
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
    <div class="title">管理员列表</div>
    <table class="list">
        <tr>
            <th>名称</th>
            <th>类别</th>
            <th>创建日期</th>
            <th>操作</th>
        </tr>
        <?php
        ConnectMysqli::connect();
        $query = 'select * from la_manager order by status';
        $result = ConnectMysqli::execute($query);
        while ($data = mysqli_fetch_assoc($result)) {
            $delete_url = urlencode("manager_delete.php?id={$data['id']}");
            $return_url = urlencode($_SERVER['REQUEST_URI']);
            $message = "你确定要删除管理员 {$data['name']} 吗？";
            $confirm_url = "confirm.php?delete_url={$delete_url}&return_url={$return_url}&message={$message}";

            if ($data['status'] == "1") {
                $data['status'] = "普通管理员";
            } else {
                $data['status'] = "超级管理员";
            }
            $html = <<<S
            <tr>
                <td>{$data['name']}</td>
                <td>{$data['status']}</td>
                <td>{$data['create_time']}</td>
                <td><a href="{$confirm_url}">[删除]</a></td>
            </tr>         
            S;
            echo $html;
        }
        ConnectMysqli::close();
        ?>
    </table>
</div>

<?php include 'inc/footer.inc.php'; ?>