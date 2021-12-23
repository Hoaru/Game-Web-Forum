<?php
class tool
{
    public static function skip($url, $message, $pic)
    {
        $html = <<<S
        <!DOCTYPE html>
        <html lang="zh-CN">
        <head>
        <meta charset="utf-8" />
        <meta http-equiv="refresh" content="3;URL={$url}"/>
        <title>正在跳转中...</title> 
        <link rel="stylesheet" type="text/css" href="style/remind.css" />
        </head>
        <body>
        <div class="notice"><span class="pic {$pic}"></span> {$message} <a href="{$url}"> 3秒后自动跳转 </a></div>
        </body>
        </html>
        S;
        echo $html;
        exit();
    }

    public static function vcode($width = 120, $height = 40, $fontSize = 30, $countElement = 4, $countPixel = 100, $countLine = 4)
    {
        header('Content-type:image/jpeg');
        $element = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $string = '';
        for ($i = 0; $i < $countElement; $i++) {
            $string .= $element[rand(0, 25)];
        }
        $img = imagecreatetruecolor($width, $height);
        $colorBg = imagecolorallocate($img, rand(200, 255), rand(200, 255), rand(200, 255));
        $colorBorder = imagecolorallocate($img, rand(200, 255), rand(200, 255), rand(200, 255));
        $colorString = imagecolorallocate($img, rand(10, 100), rand(10, 100), rand(10, 100));
        imagefill($img, 0, 0, $colorBg);
        for ($i = 0; $i < $countPixel; $i++) {
            imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), imagecolorallocate($img, rand(100, 200), rand(100, 200), rand(100, 200)));
        }
        for ($i = 0; $i < $countLine; $i++) {
            imageline($img, rand(0, $width / 2), rand(0, $height), rand($width / 2, $width), rand(0, $height), imagecolorallocate($img, rand(100, 200), rand(100, 200), rand(100, 200)));
        }
        //imagestring($img,5,0,0,'abcd',$colorString);
        imagettftext($img, $fontSize, rand(-5, 5), rand(5, 15), rand(30, 35), $colorString, 'D:/Project/PHP_project/RHbbs/font/ManyGifts.ttf', $string);
        imagejpeg($img);
        imagedestroy($img);
        return $string;
    }

    public static function is_login()
    {
        if (isset($_COOKIE['la']['name']) && isset($_COOKIE['la']['pw'])) {
            $query = "select * from la_member 
            where name = '{$_COOKIE['la']['name']}' and sha1(pw) = '{$_COOKIE['la']['pw']}'";
            ConnectMysqli::connect();
            $result = ConnectMysqli::execute($query);
            ConnectMysqli::close();
            if (mysqli_num_rows($result)) {
                $data = mysqli_fetch_assoc($result);
                return $data['id'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /*
    $count:总记录数
    $page_size:每页显示的记录数
    $btn_num:按钮数目
    $page:分页的get参数
    */

    public static function page($count, $page_size, $btn_num = 10, $page = 'page')
    {
        if ($count == 0) {
            $data = array(
                'limit' => '',
                'html' => ''
            );
            return $data;
        }

        if (!isset($_GET[$page]) || !is_numeric($_GET[$page]) || $_GET[$page] < 1) {
            $_GET[$page] = 1;
        }
        $page_count_all = ceil($count / $page_size);
        if ($_GET[$page] > $page_count_all) {
            $_GET[$page] = 10;
        }
        $start = ($_GET[$page] - 1) * $page_size;
        $limit = "limit {$start}, {$page_size}";

        $html = array();
        $current_url = $_SERVER['REQUEST_URI'];
        $arr_currnet = parse_url($current_url);
        $current_path = $arr_currnet['path'];

        if (isset($arr_currnet['query'])) {
            parse_str($arr_currnet['query'], $arr_query);
            unset($arr_query[$page]);
            if (empty($arr_query)) {
                $url = "{$current_path}?{$page}=";
            } else {
                $pri = http_build_query($arr_query);
                $url = "{$current_path}?{$pri}&{$page}=";
            }
        } else {
            $url = "{$current_path}?{$page}=";
        }

        if ($btn_num >= $page_count_all) {
            for ($i = 1; $i <= $page_count_all; $i++) {
                if ($_GET[$page] == $i) {
                    $html[$i] = "<span>{$i}</span>";
                } else {
                    $html[$i] = "<a href='{$url}{$i}'>{$i}</a>";
                }
            }
        } else {
            $num_left = floor(($btn_num - 1) / 2);
            $start = $_GET[$page] - $num_left;
            $end = $start + ($btn_num - 1);
            if ($start < 1) {
                $start = 1;
            }
            if ($end > $page_count_all) {
                $start = $page_count_all - ($btn_num - 1);
            }
            for ($i = 0; $i < $btn_num; $i++) {
                if ($_GET[$page] == $start) {
                    $html[$start] = "<span>{$start}</span>";
                } else {
                    $html[$start] = "<a href='{$url}{$start}'>{$start}</a>";
                }
                $start++;
            }
            if ($btn_num >= 3) {
                reset($html);
                $first_key = key($html);
                end($html);
                $end_key = key($html);
                if ($first_key != 1) {
                    array_shift($html);
                    array_unshift($html, "<a href='{$url}1'>1...</a>");
                }
                if ($end_key != $page_count_all) {
                    array_pop($html);
                    array_push($html, "<a href='{$url}{$page_count_all}'>...{$page_count_all}</a>");
                }
            }
        }

        if ($_GET[$page] != 1) {
            $pri = $_GET[$page] - 1;
            array_unshift($html, "<a href='{$url}{$pri}'>« 上一页</a>");
        }
        if ($_GET[$page] != $page_count_all) {
            $next = $_GET[$page] + 1;
            array_push($html, "<a href='{$url}{$next}'>下一页 »</a>");
        }

        $html = implode(' ', $html);
        $data = array(
            'limit' => $limit,
            'html' => $html
        );
        return $data;
    }

    public static function upload($save_path, $max_file_size, $key, $type = array('jpg', 'jpeg', 'gif', 'png'))
    {
        if ($_FILES[$key]['error'] != 0) {
            $return_data['error'] = "出现错误";
            $result_data['return'] = false;
            return $result_data;
        }
        if ($_FILES[$key]['size'] > $max_file_size) {
            $return_data['error'] = '上传文件过大' . $max_file_size;
            $return_data['return'] = false;
            return $return_data;
        }
        $arr_filename = pathinfo($_FILES[$key]['name']);
        if (!in_array($arr_filename['extension'], $type)) {
            $return_data['error'] = '上传文件的后缀名必须是' . implode(',', $type) . '这其中的一个';
            $return_data['return'] = false;
            return $return_data;
        }
        if (!file_exists($save_path)) {
            if (!mkdir($save_path, 0777, true)) {
                $return_data['error'] = '上传文件保存目录创建失败，请检查权限!';
                $return_data['return'] = false;
                return $return_data;
            }
        }
        $new_filename = str_replace('.', '', uniqid(mt_rand(100000, 999999), true));
        if ($arr_filename['extension'] != '') {
            $new_filename .= ".{$arr_filename['extension']}";
        }
        $save_path = rtrim($save_path, '/') . '/';
        if (!move_uploaded_file($_FILES[$key]['tmp_name'], $save_path . $new_filename)) {
            $return_data['error'] = '临时文件移动失败，请检查权限!';
            $return_data['return'] = false;
            return $return_data;
        }
        $return_data['save_path'] = $save_path . $new_filename;
        $return_data['filename'] = $new_filename;
        $return_data['return'] = true;
        return $return_data;
    }

    public static function is_manager_login() {
        if (isset($_SESSION['manager']['name']) && isset($_SESSION['manager']['pw'])) {
            return true;
        } else {
            return false;
        }
    }
}
