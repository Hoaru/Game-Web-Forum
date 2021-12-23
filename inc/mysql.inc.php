<?php
header('Content-type:text/html;charset=utf-8');
//数据库连接
class ConnectMysqli
{
    private static $DB_HOST = 'localhost';
    private static $DB_USER = 'root';
    private static $DB_PASSWORD = '123456';
    private static $DB_DATABASE = 'lalala';
    private static $DB_PORT = 3306;
    private static $link;

    public static function connect()
    {
        self::$link = mysqli_connect(self::$DB_HOST, self::$DB_USER, self::$DB_PASSWORD, self::$DB_DATABASE, self::$DB_PORT);
        if (!self::$link) {
            echo "数据库连接失败<br>";
            echo "错误编码" . mysqli_errno(self::$link) . "<br>";
            echo "错误信息" . mysqli_error(self::$link) . "<br>";
            exit();
        }
        mysqli_query(self::$link, "set names 'utf8'");
    }

    //执行一条SQL语句,返回对象集或布尔值
    public static function execute($query)
    {
        $result = mysqli_query(self::$link, $query);
        if (!$result) {
            echo "sql语句执行失败<br>";
            echo "错误编码是" . mysqli_errno(self::$link) . "<br>";
            echo "错误信息是" . mysqli_error(self::$link) . "<br>";
            exit();
        }
        return $result;
    }

    //执行一条SQL语句,只返回布尔值
    public static function execute_bool($query)
    {
        $bool = mysqli_real_query(self::$link, $query);
        return $bool;
    }

    //获取记录数
    public static function num($sql_count)
    {
        $result = self::execute($sql_count);
        if (!self::$link) {
            echo "数据库连接失败<br>";
            echo "错误编码" . mysqli_errno(self::$link) . "<br>";
            echo "错误信息" . mysqli_error(self::$link) . "<br>";
            exit();
        }
        $count = mysqli_fetch_row($result);
        return $count[0];
    }
    //数据书库之前进行转义
    public static function escape($data)
    {
        if (is_string($data)) {
            return mysqli_real_escape_string(self::$link, $data);
        }
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $data[$key] = self::escape(self::$link, $val);
            }
        }
        return $data;
    }

    //一次执行多条sql语句
    public static function execute_multi($arr_sqls, &$error)
    {
        $sqls = implode(';', $arr_sqls) . ';';
        if (mysqli_multi_query(self::$link, $sqls)) {
            $data = array();
            $i = 0; //计数
            do {
                if ($result = mysqli_store_result(self::$link, MYSQLI_STORE_RESULT_COPY_DATA)) {
                    $data[$i] = mysqli_fetch_all($result);
                    mysqli_free_result($result);
                } else {
                    $data[$i] = null;
                }
                $i++;
                if (!mysqli_more_results(self::$link)) break;
            } while (mysqli_next_result(self::$link));
            if ($i == count($arr_sqls)) {
                return $data;
            } else {
                $error = "sql语句执行失败：<br />&nbsp;数组下标为{$i}的语句:{$arr_sqls[$i]}执行错误<br />&nbsp;错误原因：" . mysqli_error(self::$link);
                return false;
            }
        } else {
            $error = '执行失败！请检查首条语句是否正确！<br />可能的错误原因：' . mysqli_error(self::$link);
            return false;
        }
    }

    //关闭数据库连接
    public static function close()
    {
        mysqli_close(self::$link);
    }
}
