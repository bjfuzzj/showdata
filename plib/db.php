<?php

//require_once ('config_inc.php');
class DBFaculty
{
    static $pub_mysql = false ;
    private function __construct(){}
    private function __clone(){}
    public static function getInstance()
    {
        if (false == self::$pub_mysql)
        {
            self::$pub_mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        return self::$pub_mysql;
    }

}
/*
require_once("config_inc.php");
$pub_mysql = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($pub_mysql->connect_errno)
{
	exit("无法选择发布系统数据库\n".$mysqli->connect_error);
}
*/
?>
