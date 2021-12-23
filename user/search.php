<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '搜索';
$template['css'] = array('style/public.css', 'style/list.css');
ConnectMysqli::connect();
if (!isset($_GET['keyword'])) {
    $_GET['keyword'] = '';
}
$_GET['keyword'] = trim($_GET['keyword']);
$_GET['keyword'] = ConnectMysqli::escape($_GET['keyword']);
$query = "select count(*) from la_content where title like '%{$_GET['keyword']}%'";
$count_all = ConnectMysqli::num($query);

$member_id = tool::is_login();
$is_manager_login = tool::is_manager_login();


ConnectMysqli::close();
?>

<?php include_once 'inc/header.inc.php' ?>

<div id="position" class="auto">
    <a href="index.php">首页</a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3>共有<?php echo $count_all ?>条帖子</h3>
            <div class="pages_wrap">
                <div class="pages">
                    <?php
                    $page = tool::page($count_all, 5, 8);
                    echo $page['html'];
                    ?>
                </div>
                <div style="clear:both;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <ul class="postsList">
            <?php
            $query = "select 
            la_content.times content_times, 
            la_content.title content_title, 
            la_content.id content_id, 
            la_member.name member_name, 
            la_member.photo member_photo, 
            la_member.id member_id, 
            la_content.time content_time 
            from la_content, la_member 
            where la_content.title like '%{$_GET['keyword']}%' 
            and la_content.member_id = la_member.id 
            order by la_content.time desc {$page['limit']}";

            ConnectMysqli::connect();
            $result_content = ConnectMysqli::execute($query);
            while ($data_content = mysqli_fetch_assoc($result_content)) {
                $data_content['content_title'] = htmlspecialchars($data_content['content_title']);
                $data_content['title_color']=str_replace($_GET['keyword'],"<span style='color:red;'>{$_GET['keyword']}</span>",$data_content['content_title']);
                $query = "select count(*) from la_reply where 
                content_id = {$data_content['content_id']}";
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
                        <a href="member.php?id=<?php echo $data_content['member_id']; ?>">
                            <img width="45" height="45" src="<?php if ($data_content['member_photo'] != '') {
                                                                    echo SUB_URL . $data_content['member_photo'];
                                                                } else {
                                                                    echo 'style/photo.jpg';
                                                                } ?>">
                        </a>
                    </div>
                    <div class="subject">
                        <div class="titleWrap"><a href="#"> </a>
                            <h2><a target="_blank" href="show.php?id=<?php echo $data_content['content_id'] ?>"><?php echo $data_content['title_color'] ?></a></h2>
                        </div>
                        <p>
                            楼主：<?php echo $data_content['member_name'] ?>&nbsp;<?php echo $data_content['content_time'] ?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $reply_time; ?><br />
                            <?php
                            if ($member_id == $data_content['member_id'] || $is_manager_login) {
                                $return_url = urlencode($_SERVER['REQUEST_URI']);
                                $delete_url = urlencode("content_delete.php?id={$data_content['content_id']}&return_url={$return_url}");
                                $message = "你确定要删除帖子 {$data_content['content_title']} 吗？";
                                $confirm_url = "confirm.php?delete_url={$delete_url}&return_url={$return_url}&message={$message}";
                                echo "<a href = '{$confirm_url}'>删除</a>";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="count">
                        <p>
                            回复<br /><span><?php echo $count_reply ?></span>
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
        <div class="pages_wrap">
            <div class="pages">
                <?php
                echo $page['html'];
                ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div id="right">
        <div class="classList">
            <div class="title">版块列表</div>
            <ul class="listWrap">
                <?php
                ConnectMysqli::connect();
                $query = "select * from la_father_module order by sort";
                $result_father = ConnectMysqli::execute($query);
                while ($data_father = mysqli_fetch_assoc($result_father)) {
                ?>
                    <li>
                        <h2><a href="list_father.php?id=<?php echo $data_father['id']; ?>"><?php echo $data_father['module_name']; ?></a></h2>
                        <ul>
                            <?php
                            $query = "select * from la_son_module where 
                        father_module_id = '{$data_father['id']}' order by sort";
                            $result_son = ConnectMysqli::execute($query);
                            while ($data_son = mysqli_fetch_assoc($result_son)) {
                            ?>
                                <li>
                                    <h3><a href="list_son.php?id=<?php echo $data_son['id']; ?>"><?php echo $data_son['module_name']; ?></a></h3>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </li>

                <?php } ?>
                <?php
                ConnectMysqli::close();
                ?>
            </ul>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<?php include_once 'inc/footer.inc.php' ?>