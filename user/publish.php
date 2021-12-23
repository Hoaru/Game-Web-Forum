<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '发帖';
$template['css'] = array('style/public.css', 'style/publish.css');

if (!$member_id = tool::is_login()) {
    tool::skip('login.php', '未登录不能发帖', 'error');
}

if (isset($_POST['submit'])) {
    include_once 'inc/check_publish.inc.php';

    ConnectMysqli::connect();
    $_POST['title'] = ConnectMysqli::escape($_POST['title']);
    $_POST['content'] = ConnectMysqli::escape($_POST['content']);

    $query = "insert into la_content(module_id, title, content, time, member_id) 
    values('{$_POST['module_id']}', '{$_POST['title']}', '{$_POST['content']}', now(), '{$member_id}')";

    $result = ConnectMysqli::execute($query);
    ConnectMysqli::close();
    if ($result) {
        tool::skip('index.php', '发布成功', 'ok');
    } else {
        tool::skip('publish.php', '发布失败请重试', 'error');
    }
}

?>

<?php include_once 'inc/header.inc.php' ?>

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; 发布帖子
</div>
<div id="publish">
    <form method="post">
        <select name="module_id">
            <option value=-1>请选择一个板块</option>
            <?php
            $where = '';
            if (isset($_GET['father_id']) && is_numeric($_GET['father_id'])) {
                $where = "where id = {$_GET['father_id']} ";
            }
            $query = "select * from la_father_module {$where}order by sort";
            ConnectMysqli::connect();
            $result_father = ConnectMysqli::execute($query);
            while ($data_father = mysqli_fetch_assoc($result_father)) {
                echo "<optgroup label='{$data_father['module_name']}'>";
                $query = "select * from la_son_module
                where father_module_id = '{$data_father['id']}' 
                order by sort";
                $result_son = ConnectMysqli::execute($query);
                while ($data_son = mysqli_fetch_assoc($result_son)) {
                    if (isset($_GET['son_id']) && $_GET['son_id'] == $data_son['id']) {
                        echo "<option selected='selected' value = '{$data_son['id']}' >{$data_son['module_name']}</option>";
                    } else {
                        echo "<option value = '{$data_son['id']}' >{$data_son['module_name']}</option>";
                    }
                }
                echo "</optgroup>";
            }
            ConnectMysqli::close();
            ?>
        </select>
        <input class="title" placeholder="请输入标题" name="title" type="text" />
        <textarea name="content" class="content"></textarea>
        <input class="publish" type="submit" name="submit" value="发帖" />
        <div style="clear:both;"></div>
    </form>
</div>


<?php include_once 'inc/footer.inc.php' ?>