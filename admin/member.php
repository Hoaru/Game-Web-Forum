<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '用户列表';
$template['css'] = array('style/public.css');
include_once 'inc/is_manager_login.inc.php';


?>

<?php include 'inc/header.inc.php'; ?>

<div id="main">
    <div class="title">管理员列表</div>
    <table class="list">
        <tr>
            <th>id</th>
            <th>名称</th>
            <th>注册日期</th>
            <th>操作</th>
        </tr>
        <?php
        ConnectMysqli::connect();
        $query = 'select * from la_member';
        $result = ConnectMysqli::execute($query);
        while ($data = mysqli_fetch_assoc($result)) {
            $delete_url = urlencode("member_delete.php?id={$data['id']}");
            $return_url = urlencode($_SERVER['REQUEST_URI']);
            $message = "你确定要删除用户 {$data['name']} 吗？";
            $confirm_url = "confirm.php?delete_url={$delete_url}&return_url={$return_url}&message={$message}";

            $html = <<<S
            <tr>
                <td>{$data['id']}</td>
                <td>{$data['name']}</td>
                <td>{$data['register_time']}</td>
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