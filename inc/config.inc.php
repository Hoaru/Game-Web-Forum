<?php
session_start();
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
// define("DB_HOST", 'localhost');
// define("DB_USER", 'root');
// define('DB_PASSWORD', '123456');
// define('DB_DATABASE', 'lalala');
// define('DB_PORT', 3306);
//在服务器上的绝对路径
define('SA_PATH',dirname(dirname(__FILE__)));
//在web根目录下面的位置（哪个目录里面）
define('SUB_URL_T',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('\\','/',SA_PATH)).'/');
define('SUB_URL', SUB_URL_T . "user/");

?>