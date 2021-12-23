<?php
include_once '../inc/mysql.inc.php';
include_once '../inc/config.inc.php';
include_once '../inc/tool.inc.php';

if (!$member_id = tool::is_login()) {
    tool::skip('login.php', '请登录', 'error');
}

ConnectMysqli::connect();
$query = "select * from la_member where id = {$_GET['id']}";
$result = ConnectMysqli::execute($query);
$data_member = mysqli_fetch_assoc($result);
if (isset($_POST['submit'])) {
    $save_path = 'uploads/' . date('Y/m/d/');
    $upload = tool::upload($save_path, '819200', 'photo');
    if ($upload['return']) {
        $query = "update la_member set photo = '{$upload['save_path']}' where 
        id = {$member_id}";
        $result = ConnectMysqli::execute_bool($query);
        if ($result) {
            tool::skip("member.php?id={$member_id}", '设置成功', 'ok');
        } else {
            tool::skip("member_photo_modify.php?id={$member_id}", '设置失败', 'error');
        }
    } else {
        tool::skip("member_photo_modify.php?id={$member_id}", '上传文件错误', 'error');
    }
}
ConnectMysqli::close();
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title>修改头像</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <style type="text/css">
        body {
            font-size: 12px;
            font-family: 微软雅黑;
        }

        h2 {
            padding: 0 0 10px 0;
            border-bottom: 1px solid #e3e3e3;
            color: #444;
        }

        .submit {
            background-color: #3b7dc3;
            color: #fff;
            padding: 5px 22px;
            border-radius: 2px;
            border: 0px;
            cursor: pointer;
            font-size: 14px;
        }

        #main {
            width: 80%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div id="main">
        <h2>更改头像</h2>
        <div>
            <h3>原头像：</h3>
            <img width="180" height="180" src="<?php if ($data_member['photo'] != '') {
                            echo SUB_URL . $data_member['photo'];
                        } else {
                            echo 'style/photo.jpg';
                        } ?>" />
            <br />
            最佳图片尺寸：180*180
        </div>
        <div style="margin:15px 0 0 0;">
            <form method="post" enctype="multipart/form-data">
                <input style="cursor:pointer;" width="100" type="file" name="photo" /><br /><br />
                <input class="submit" type="submit" name="submit" value="保存" />
            </form>
        </div>
    </div>
</body>

</html>