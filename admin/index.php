<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

include_once 'inc/is_manager_login.inc.php';

$template['title'] = '系统信息';
$template['css'] = array('style/public.css');

$query = "select * from la_manager where id = {$_SESSION['manager']['id']}";
ConnectMysqli::connect();
$result_manager = ConnectMysqli::execute($query);
$data_manager = mysqli_fetch_assoc($result_manager);
if ($data_manager['status'] == "1") {
    $data_manager['status'] = '普通管理员';
} else {
    $data_manager['status'] = '超级管理员';
}

$query = "select count(*) from la_father_module";
$count_father_module = ConnectMysqli::num($query);

$query = "select count(*) from la_son_module";
$count_son_module = ConnectMysqli::num($query);

$query = "select count(*) from la_content";
$count_content = ConnectMysqli::num($query);

$query = "select count(*) from la_reply";
$count_reply = ConnectMysqli::num($query);

$query = "select count(*) from la_member";
$count_member = ConnectMysqli::num($query);

$query = "select count(*) from la_manager";
$count_manager = ConnectMysqli::num($query);

ConnectMysqli::close();
?>

<?php include_once 'inc/header.inc.php' ?>
<div id="main">
	<div class="title">系统信息</div>
	<div class="explain">
		<ul>
			<li>|- 您好，<?php echo $data_manager['name']?></li>
			<li>|- 所属角色：<?php echo $data_manager['status']?> </li>
			<li>|- 创建时间：<?php echo $data_manager['create_time']?></li>
		</ul>
	</div>
	<div class="explain">
		<ul>
			<li>|- 父版块(<?php echo $count_father_module?>)</li>
			<li>|- 子版块(<?php echo $count_son_module?>)</li>
			<li>|- 帖子(<?php echo $count_content?>)</li>
			<li>|- 回复(<?php echo $count_reply?>)</li>
			<li>|- 会员(<?php echo $count_member?>)</li>
            <li>|- 管理员(<?php echo $count_manager?>)</li>
			</li>
		</ul>
	</div>
	
</div>
<?php include_once 'inc/footer.inc.php'; ?>