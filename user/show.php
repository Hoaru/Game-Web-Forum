<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = 'lalala论坛';
$template['css'] = array('style/public.css', 'style/show.css');

$member_id = tool::is_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('index.php', 'id参数错误', 'error');
}
$query = "select 
lc.id content_id, 
lc.module_id module_id, 
lc.title content_title, 
lc.content content, 
lc.time time, 
lc.member_id member_id, 
lc.times times, 
lm.name member_name, 
lm.photo member_photo 
from la_content lc, la_member lm where 
lc.id = {$_GET['id']} and 
lc.member_id = lm.id";
ConnectMysqli::connect();
$result_content = ConnectMysqli::execute($query);
if (!mysqli_num_rows($result_content)) {
    tool::skip('index.php', '帖子不存在', 'error');
}
$query = "update la_content set times = times + 1 where 
id = {$_GET['id']}";
ConnectMysqli::execute($query);
$data_content = mysqli_fetch_assoc($result_content);

$data_content['content_title'] = htmlspecialchars($data_content['content_title']);
$data_content['content'] = nl2br(htmlspecialchars($data_content['content']));

$query = "select * from la_son_module where 
id = {$data_content['module_id']}";

$result_son = ConnectMysqli::execute($query);
$data_son = mysqli_fetch_assoc($result_son);
$query = "select * from la_father_module where 
id = {$data_son['father_module_id']}";
$result_father = ConnectMysqli::execute($query);
$data_father = mysqli_fetch_assoc($result_father);


ConnectMysqli::close();
?>

<?php include_once 'inc/header.inc.php' ?>

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt;
    <a href="list_father.php?id=<?php echo $data_father['id']; ?>"><?php echo  $data_father['module_name']; ?></a> &gt;
    <a href="list_son.php?id=<?php echo $data_son['id']; ?>"><?php echo  $data_son['module_name']; ?></a> &gt;
    <a href="show.php?id=<?php echo $data_content['content_id']; ?>"><?php echo  $data_content['content_title']; ?></a>

</div>
<div id="main" class="auto">
    <div class="wrap1">
        <div class="pages">
            <?php
            $page_size = 3;
            ConnectMysqli::connect();
            $query = "select count(*) from la_reply where content_id = {$_GET['id']}";
            $count_reply = ConnectMysqli::num($query);
            $page = tool::page($count_reply, $page_size);
            echo $page['html'];
            ConnectMysqli::close();
            ?>
        </div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']; ?>"></a>
        <div style="clear:both;"></div>
    </div>
    <?php 
    if (!isset($_GET['page']) || $_GET['page'] == 1){
    ?>
    <div class="wrapContent">
        <div class="left">
            <div class="face">
                <a target="_blank" href="">
                    <img width="120" height="120" src="<?php if ($data_content['member_photo'] != '') {echo $data_content['member_photo'];} else {echo 'style/photo.jpg';}?>" />
                </a>
            </div>
            <div class="name">
                <a href=""><?php echo $data_content['member_name'] ?></a>
            </div>
        </div>
        <div class="right">
            <div class="title">
                <h2><?php echo $data_content['content_title'] ?></h2>
                <span>阅读：<?php echo $data_content['times'] ?>&nbsp;|&nbsp;回复：<?php echo $count_reply; ?></span>
                <div style="clear:both;"></div>
            </div>
            <div class="pubdate">
                <span class="date">发布于：<?php echo $data_content['time'] ?> </span>
                <span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
            </div>
            <div class="content">
                <?php echo $data_content['content'] ?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <?php
    }
    ?>
    <?php
    $query = "select 
    lm.name member_name, 
    lm.id member_id, 
    lm.photo member_photo, 
    lr.id reply_id, 
    lr.content reply_content, 
    lr.time reply_time 
    from 
    la_reply lr, 
    la_member lm 
    where 
    lr.member_id = lm.id and 
    lr.content_id = {$_GET['id']} 
    order by time desc 
    {$page['limit']}";
    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
    }

    $i = ($_GET['page'] - 1) * $page_size + 1;
    ConnectMysqli::connect();
    $result_reply = ConnectMysqli::execute($query);
    while ($data_reply = mysqli_fetch_assoc($result_reply)){
        $data_reply['reply_content'] = nl2br(htmlspecialchars($data_reply['reply_content']));
    ?>
    <div class="wrapContent">
        <div class="left">
            <div class="face">
                <a target="_blank" href="">
                    <img width="120" height="120" src="<?php if ($data_content['member_photo'] != '') {echo $data_content['member_photo'];} else {echo 'style/photo.jpg';}?>" />
                </a>
            </div>
            <div class="name">
                <a href=""><?php echo $data_reply['member_name']; ?></a>
            </div>
        </div>
        <div class="right">

            <div class="pubdate">
                <span class="date">回复时间：<?php echo $data_reply['reply_time']; ?></span>
                <span class="floor"><?php echo $i++; ?>楼&nbsp;|&nbsp;<a href="#">引用</a></span>
            </div>
            <div class="content">
            <?php echo $data_reply['reply_content']; ?>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <?php
    }
    ?>
    <div class="wrap1">
        <div class="pages">
            <?php
            echo $page['html'];
            ?>
        </div>
        <a class="btn reply" href="reply.php?id=<?php echo $_GET['id']; ?>" ></a>
        <div style="clear:both;"></div>
    </div>
</div>

<?php include_once 'inc/footer.inc.php' ?>