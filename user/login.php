<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

$template['title'] = '登录';
$template['css'] = array('style/public.css', 'style/register.css');
$member_id = tool::is_login();
if ($member_id) {
    tool::skip('index.php', '您已登录', 'error');
}

if (isset($_POST['submit'])) {
    include_once 'inc/check_login.inc.php';

    ConnectMysqli::connect();
    $_POST['name'] = ConnectMysqli::escape($_POST['name']);
    $query = "select * from la_member 
    where name = '{$_POST['name']}' and pw = md5('{$_POST['pw']}')";
    $result = ConnectMysqli::execute($query);
    ConnectMysqli::close();
    if (mysqli_num_rows($result)) {
        setcookie('la[name]', $_POST['name'], time() + $_POST['time']);
        setcookie('la[pw]', sha1(md5($_POST['pw'])), time() + $_POST['time']);
        tool::skip('index.php', '登录成功', 'ok');
    } else {
        tool::skip('register.php', '用户名或密码错误！', 'error');
    }
    
}

?>



<?php include_once 'inc/header.inc.php' ?>

<div id="register" class="auto">
    <h2>登录</h2>
    <form method="post">
        <label>用户名：<input type="text" name="name" /><span></span></label>
        <label>密码：<input type="password" name="pw" /><span></span></label>
        <label>验证码：<input type="text" name="vcode" /><span>*请输入下方验证码</span></label>
        <img class="vcode" src="show_code.php" />
        <label>自动登录：
            <select style="width:236px;height:25px;" name="time">
                <option value=3600>1小时内</option>
                <option value=86400>1天内</option>
                <option value=259200>3天内</option>
                <option value=2592000>30天内</option>
            </select>
            <span>*公共电脑上请勿长期自动登录</span>
        </label>
        <div style="clear:both;"></div>
        <input class="btn" type="submit" name="submit" value="登录" />
    </form>
</div>


<?php include_once 'inc/footer.inc.php' ?>