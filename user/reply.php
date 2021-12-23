<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '发帖';
$template['css'] = array('style/public.css', 'style/publish.css');

if (!$member_id = tool::is_login()) {
    tool::skip('login.php', '请登录之后再回复', 'error');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('index.php', 'id参数错误', 'error');
}

ConnectMysqli::connect();
$query = "select 
lc.id content_id, 
lc.title content_title, 
lm.id member_id, 
lm.name member_name 
from la_content lc, la_member lm 
where lc.id = {$_GET['id']} and 
lc.member_id = lm.id";
$result_content = ConnectMysqli::execute($query);
if (!mysqli_num_rows($result_content)) {
    tool::skip('index.php', '帖子不存在', 'error');
}
$data_content = mysqli_fetch_assoc($result_content);

ConnectMysqli::close();

if (isset($_POST['submit'])) {
    if (empty($_POST['content'])) {
        tool::skip($_SERVER['REQUEST_URI'], '回复内容不能为空', 'error');
    } else {
        ConnectMysqli::connect();
        $_POST['content'] = ConnectMysqli::escape($_POST['content']);
        $query = "insert into la_reply(content_id, content, time, member_id) 
        values('{$data_content['content_id']}', 
        '{$_POST['content']}', 
        now(), 
        '{$member_id}')";
        $result = ConnectMysqli::execute_bool($query);
        if ($result) {
            tool::skip("show.php?id={$_GET['id']}", '回复成功', 'ok');
        } else {
            tool::skip($_SERVER['REQUEST_URI'], '回复失败', 'error');
        }
    }
}


?>

<?php include_once 'inc/header.inc.php' ?>

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; 回复帖子
</div>
<div id="publish">
    <div>回复：由 <?php echo $data_content['member_name']; ?> 发布的 <?php echo $data_content['content_title']; ?></div>
    <form method="post">
        <textarea name="content" class="content"></textarea>
        <input class="reply" type="submit" name="submit" value="" />
        <div style="clear:both;"></div>
    </form>
</div>

<?php include_once 'inc/footer.inc.php' ?>