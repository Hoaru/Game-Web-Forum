<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = 'lalala论坛';
$template['css'] = array('style/public.css', 'style/index.css');

$member_id = tool::is_login();

?>

<?php include_once 'inc/header.inc.php' ?>



<?php
ConnectMysqli::connect();
$query = "select * from la_father_module order by sort";
$result_father = ConnectMysqli::execute($query);
while ($data_father = mysqli_fetch_assoc($result_father)) {
?>
	<div class="box auto">
		<div class="title">
			<a href="list_father.php?id=<?php echo $data_father['id'] ?>" style="color: #105cb6"><?php echo $data_father['module_name']; ?></a>
		</div>
		<div class="classList">
			<?php
			$query = "select * from la_son_module 
			where father_module_id = '{$data_father['id']}'";
			$result_son = ConnectMysqli::execute($query);
			if (mysqli_num_rows($result_son)) {
				while ($data_son = mysqli_fetch_assoc($result_son)) {
					$query = "select count(*) from la_content where module_id = '{$data_son['id']}' and time > CURDATE()";
					$count_today = ConnectMysqli::num($query);

					$query = "select count(*) from la_content where module_id = '{$data_son['id']}'";
					$count_all = ConnectMysqli::num($query);

					$html = <<<S
					<div class="childBox old">
						<h2><a href="list_son.php?id={$data_son['id']}">{$data_son['module_name']}</a> <span>(今日{$count_today})</span></h2>
						帖子：{$count_all}<br />
					</div>
					S;
					echo $html;
				}
			} else {
				echo '<div style="padding:10px 0;">暂无子版块...</div>';
			}
			?>
			<div style="clear:both;"></div>
		</div>
	</div>
<?php } ?>

<?php
ConnectMysqli::close();
?>


<?php include_once 'inc/footer.inc.php' ?>