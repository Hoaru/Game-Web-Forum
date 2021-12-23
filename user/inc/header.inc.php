<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <title><?php echo $template['title']; ?></title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <?php
    foreach ($template['css'] as $val) {
        echo "<link rel='stylesheet' type='text/css' href='{$val}' />";
    }
    ?>
</head>

<body>
    <div class="header_wrap">
        <div id="header" class="auto">
            <div class="logo">lalala</div>
            <div class="nav">
                <a class="hover" href="index.php">首页</a>
            </div>
            <div class="serarch">
                <form action="search.php">
                    <input class="keyword" type="text" name="keyword" value="<?php if (isset($_GET['keyword'])) echo $_GET['keyword']; ?>" placeholder="搜索" />
                    <input class="submit" type="submit" name="submit" value="" />
                </form>
            </div>
            <div class="login">
                <?php 
                if (isset($member_id) && $member_id) {
                    $html = <<<S
                    <a href = "member.php?id={$member_id}">您好！{$_COOKIE['la']['name']}</a>&nbsp;
                    <span style="color:#fff;">|</span>&nbsp;
                    <a href = "logout.php">注销</a>&nbsp;
                    S;
                    echo $html;
                } else {
                    $html = <<<S
                    <a href="login.php">登录</a>&nbsp;
                    <a href="register.php">注册</a>
                    S;
                    echo $html;
                }
                
                ?>
                
            </div>
        </div>
    </div>
    <div style="margin-top:55px;"></div>