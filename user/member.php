<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '会员中心';
$template['css'] = array('style/public.css', 'style/list.css', 'style/member.css');

$member_id = tool::is_login();
$is_manager_login = tool::is_manager_login();
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('index.php', 'id参数错误', 'error');
}
ConnectMysqli::connect();
$query = "select * from la_member where id = {$_GET['id']}";
$result_member = ConnectMysqli::execute($query);
if (!mysqli_num_rows($result_member)) {
    tool::skip('index.php', '会员不存在', 'error');
}
$data_member = mysqli_fetch_assoc($result_member);

$query = "select count(*) from la_content where 
member_id = {$_GET['id']}";
$count_all = ConnectMysqli::num($query);


ConnectMysqli::close();
?>

<?php include_once 'inc/header.inc.php' ?>

<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <?php echo $data_member['name']; ?>
</div>
<div id="main" class="auto">
    <div id="left">
        <ul class="postsList">
            <?php
            $page = tool::page($count_all, 5);
            ConnectMysqli::connect();
            $query = "select 
            la_content.times content_times, 
            la_content.title content_title, 
            la_content.id content_id, 
            la_content.member_id member_id, 
            la_member.name member_name, 
            la_member.photo member_photo, 
            la_content.time content_time 
            from la_content, la_member 
            where la_content.member_id = {$_GET['id']} 
            and la_content.member_id = la_member.id order by time desc {$page['limit']}";



            $result_content = ConnectMysqli::execute($query);
            while ($data_content = mysqli_fetch_assoc($result_content)) {
                $query = "select count(*) from la_reply where content_id = {$data_content['content_id']}";
                $count_reply = ConnectMysqli::num($query);

                $query = "select time from la_reply where 
                          content_id = {$data_content['content_id']} order by id desc";
                $result_reply = ConnectMysqli::execute($query);
                if (mysqli_num_rows($result_reply) == 0) {
                    $reply_time = "暂无";
                } else {
                    $data_reply = mysqli_fetch_assoc($result_reply);
                    $reply_time = $data_reply['time'];
                }
            ?>
                <li>
                    <div class="smallPic">
                        <img width="45" height="45" src="<?php if ($data_content['member_photo'] != '') {
                                                                echo SUB_URL . $data_content['member_photo'];
                                                            } else {
                                                                echo 'style/photo.jpg';
                                                            } ?>" />
                    </div>
                    <div class="subject">
                        <div class="titleWrap"><a href="#"> </a>&nbsp;&nbsp;
                            <h2><a target="_blank" href="show.php?id=<?php echo $data_content['content_id'] ?>"><?php echo $data_content['content_title'] ?></a></h2>
                        </div>
                        <p>
                            <?php
                            if ($member_id == $data_content['member_id'] || $is_manager_login) {
                                $delete_url = urlencode("content_delete.php?id={$data_content['content_id']}");
                                $return_url = urlencode($_SERVER['REQUEST_URI']);
                                $message = "你确定要删除帖子 {$data_content['content_title']} 吗？";
                                $confirm_url = "confirm.php?delete_url={$delete_url}&return_url={$return_url}&message={$message}";
                                echo "&nbsp;&nbsp;&nbsp;<a href = '{$confirm_url}'>删除</a>";
                            }
                            ?>
                            &nbsp;&nbsp;发布日期：<?php echo $data_content['content_time'] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $reply_time; ?>
                        </p>
                    </div>
                    <div class="count">
                        <p>
                            回复<br /><span><?php echo $count_reply; ?></span>
                        </p>
                        <p>
                            浏览<br /><span><?php echo $data_content['content_times'] ?></span>
                        </p>
                    </div>
                    <div style="clear:both;"></div>
                </li>
            <?php
            }
            ?>

            <?php
            ConnectMysqli::close();
            ?>
        </ul>
        <div class="pages">
            <?php
            echo $page['html'];
            ?>
        </div>
    </div>
    <div id="right">
        <div class="member_big">
            <dl>
                <dt>
                    <img width="180" height="180" src="<?php if ($data_member['photo'] != '') {
                                                            echo SUB_URL . $data_member['photo'];
                                                        } else {
                                                            echo 'style/photo.jpg';
                                                        } ?>" />
                </dt>
                <dd class="name"><?php echo $data_member['name']; ?></dd>
                <dd>帖子总计：<?php echo $count_all ?></dd>
                <?php
                if ($member_id == $data_member['id']) {
                ?>
                <dd>操作：<a target="_blank" href="member_photo_modify.php?id=<?php echo $data_member['id']; ?>">修改头像</a></dd>
                <?php
                }
                ?>
            </dl>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<?php include_once 'inc/footer.inc.php' ?>