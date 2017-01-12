<?php
ini_set('display_errors', true);
require_once("plib/head.php");
//require_once("plib/config_inc.php");
//require_once("plib/global_func.php");

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
class Index
{
    public function __construct()
    {}

    public function index()
    {
        include_once ('templates/index.php');
    }
}

?>