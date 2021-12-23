<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '注册';
$template['css'] = array('style/public.css', 'style/register.css');

if (isset($_POST['submit'])) {
	include 'inc/check_register.inc.php';

	ConnectMysqli::connect();
	$query = "insert into la_member(name, pw, register_time) 
    values('{$_POST['name']}', md5('{$_POST['pw']}'), now())";
	$result = ConnectMysqli::execute($query);
	ConnectMysqli::close();
	if ($result) {
		setcookie('la[name]', $_POST['name']);
		setcookie('la[pw]', sha1(md5($_POST['pw'])));
		tool::skip('index.php', '注册成功', 'ok');
	} else {
		tool::skip('register.php', '注册失败请重试', 'error');
	}
}
?>

<?php include_once 'inc/header.inc.php' ?>

<div id="register" class="auto">
	<h2>欢迎注册成为 lalala会员</h2>
	<form method="post">
		<label>用户名：<input type="text" name="name" /><span>*用户名不得为空，并且长度不得超过16个字符</span></label>
		<label>密码：<input type="password" name="pw" /><span>*密码不得少于6位</span></label>
		<label>确认密码：<input type="password" name="confirm_pw" /><span>*请再次输入密码</span></label>
		<label>验证码：<input name="vcode" type="text" name="vcode" /><span>*请输入下方验证码</span></label>
		<img class="vcode" src="show_code.php" />
		<div style="clear:both;"></div>
		<input class="btn" type="submit" name="submit" value="注册" />
	</form>
</div>

<?php include_once 'inc/footer.inc.php' ?>