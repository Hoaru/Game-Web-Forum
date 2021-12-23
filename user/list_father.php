<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = 'lalala论坛';
$template['css'] = array('style/public.css', 'style/list.css');

$is_manager_login = tool::is_manager_login();
$member_id = tool::is_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    tool::skip('index.php', 'id参数错误', 'error');
}

$query = "select * from la_father_module where id = '{$_GET['id']}'";
ConnectMysqli::connect();
$result_father = ConnectMysqli::execute($query);
if (!mysqli_num_rows($result_father)) {
    tool::skip('index.php', '父板块不存在', 'error');
} else {
    $data_father = mysqli_fetch_assoc($result_father);
}

$query = "select * from la_son_module where father_module_id = {$_GET['id']}";
$result_son = ConnectMysqli::execute($query);
$id_son = '';
$son_list = '';
while ($data_son = mysqli_fetch_assoc($result_son)) {
    $id_son .= $data_son['id'] . ',';
    $son_list .= "<a href='list_son.php?id={$data_son['id']}'?>{$data_son['module_name']}</a> ";
}
$id_son = trim($id_son, ',');
$son_list = trim($son_list, ' ');

if ($id_son == '') {
    $son_son = -1;
}

$query = "select count(*) from la_content where module_id in({$id_son})";
$count_all = ConnectMysqli::num($query);

$query = "select count(*) from la_content where module_id in({$id_son}) and time > CURDATE()";
$count_today = ConnectMysqli::num($query);

ConnectMysqli::close();
?>

<?php include_once 'inc/header.inc.php' ?>


<div id="position" class="auto">
    <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id']; ?>"><?php echo  $data_father['module_name']; ?></a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3><?php echo  $data_father['module_name']; ?></h3>
            <div class="num">
                今日：<span><?php echo $count_today; ?></span>&nbsp;&nbsp;&nbsp;
                总帖：<span><?php echo $count_all; ?></span>
                <div class="moderator"> 子版块： <?php echo $son_list; ?></div>
            </div>
            <div class="pages_wrap">
                <a class="btn publish" href="publish.php?father_id=<?php echo $_GET['id'] ?>" target="_blank"></a>
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
            ConnectMysqli::connect();

            $query = "select 
            la_content.times content_times, 
            la_content.title content_title, 
            la_content.id content_id, 
            la_member.name member_name, 
            la_member.photo member_photo, 
            la_member.id member_id, 
            la_content.time content_time, 
            la_son_module.module_name son_name, 
            la_son_module.id son_id 
            from la_content, la_member, la_son_module 
            where la_content.module_id in({$id_son}) 
            and la_content.member_id = la_member.id 
            and la_content.module_id = la_son_module.id 
            order by la_content.time desc {$page['limit']}";

            $result_content = ConnectMysqli::execute($query);

            while ($data_content = mysqli_fetch_assoc($result_content)) {
                $data_content['content_title'] = htmlspecialchars($data_content['content_title']);
                $query = "select time from la_reply where 
                content_id = {$data_content['content_id']} order by id desc";
                $result_reply = ConnectMysqli::execute($query);
                if (mysqli_num_rows($result_reply) == 0) {
                    $reply_time = "暂无";
                } else {
                    $data_reply = mysqli_fetch_assoc($result_reply);
                    $reply_time = $data_reply['time'];
                }
                $query = "select count(*) from la_reply where 
                content_id = {$data_content['content_id']}";
                $count_reply = ConnectMysqli::num($query);
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
                        <div class="titleWrap">
                            <a href="list_son.php?id=<?php echo $data_content['son_id']; ?>">[<?php echo $data_content['son_name']; ?>] </a>
                            &nbsp;&nbsp;<h2><a target="_blank" href="show.php?id=<?php echo $data_content['content_id'] ?>"><?php echo $data_content['content_title'] ?></a></h2>
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
        <div class="pages_wrap">
            <a class="btn publish" href="publish.php?father_id=<?php echo $_GET['id'] ?>" target="_blank"></a>
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