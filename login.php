<?php
//ini_set('display_errors', true);
require_once("plib/head.php");

$cgi = getCGI();
$c = isset($cgi['c']) ? $cgi['c'] : 'index';
$a = isset($cgi['a']) ? $cgi['a'] : 'index';

if (in_array($c,$callAbleClass) && method_exists($c,$a))
{
    $controllerClass = new $c();
    $controllerClass->$a();
}
else
{
    include_once ("404.php");
}

class Login
{
	public function __construct(){}

	public function login()
	{
		$pub_mysql = DBFaculty::getInstance();
		$cgi =  getCGI();
		$admin=$cgi["admin"];
		$pwd=$cgi['pwd'];
		$g_code=$cgi['g_code'];
		if($admin == "" || $pwd  == "") sys_exit("参数错误");
		$sqlstr ="select id, login, name, type, priv, allproj,  passwd,secret, salt from user where login='$admin'";
		$res = $pub_mysql->query($sqlstr) or sys_exit("系统忙， 请稍候再试。", $sqlstr . ":\n" . $pub_mysql->error);
		$row_user = $res->fetch_array(MYSQLI_ASSOC);

		if($row_user == "") sys_exit("用户{$admin}不存在");

		$cgi_u_id=$row_user['id'];
		$cgi_u_login=$row_user['login'];
		$cgi_u_name=$row_user['name'];
		$cgi_u_type=$row_user['type'];
		$cgi_u_priv=$row_user['priv'];
		$cgi_u_allproj = $row_user['allproj'];
		$db_pwd = $row_user['passwd'];
		$salt = $row_user['salt'];
		if(md5($pwd.$salt) != $db_pwd)
		{
			$sqlstr = "update user set f_times=f_times+1 where login='$admin'";
			$res = $pub_mysql->query($sqlstr) or sys_exit("系统忙， 请稍候再试。", $sqlstr . ":\n" . $pub_mysql->error);
			sys_exit("用户 $admin 密码错误");
		}
        //  google-authenticator 验证
		$ga= new PHPGangsta_GoogleAuthenticator();
		$db_secret=$row_user['secret'];
        //$one_code = $ga->getCode($db_secret); //服务端计算"一次性验证码"
		$checkResult = $ga->verifyCode($db_secret, $g_code, 2);
		if(!$checkResult)
		{
			$sqlstr = "update user set f_times=f_times+1 where login='$admin'";
			$res = $pub_mysql->query($sqlstr) or sys_exit("系统忙， 请稍候再试。", $sqlstr . ":\n" . $pub_mysql->error);
			sys_exit("用户验证码错误");
		}

		$ck_u_priv = "";
		$sqlstr =  "select p_id from user_priv where u_id='$cgi_u_id'";
		$res = $pub_mysql->query($sqlstr) or sys_exit("系统忙， 请稍候再试。", $sqlstr . ":\n" . $pub_mysql->error);
		while($row = $res->fetch_array())
		{
			$ck_u_priv .= ",{$row['p_id']}";
		}

		$sqlstr =  "select p_id from proj where u_id='$cgi_u_id'";
		$res = $pub_mysql->query($sqlstr) or sys_exit("系统忙， 请稍候再试。", $sqlstr . ":\n" . $pub_mysql->error);
		while($row = $res->fetch_array())
		{
			$ck_u_priv .=  ",{$row['p_id']}";
		}


		$row_user['priv'] = $ck_u_priv;

		$sqlstr = "update user set s_times=s_times+1, lastlogin=now() where login='$admin'";
		$res = $pub_mysql->query($sqlstr) or sys_exit("系统忙， 请稍候再试。", $sqlstr . ":\n" . $pub_mysql->error);
		reset_cookie($row_user);

		if($cgi_u_type < 100)
		{
			print("<script type=\"text/javascript\"> window.location= 'projlist.php' </script>");
		}
		else
		{
			print("<script type=\"text/javascript\"> window.location= 'projlist.php' </script>");
		}
	}
	public function logout()
	{

	}
}


?>
