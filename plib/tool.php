<?php
/**
 * Created by PhpStorm.
 * User: bjfuzzj
 * Date: 16/11/10
 * Time: 23:49
 */
class Tool
{

    public static function getCGI()
    {
        $cgi=array();
        foreach($_POST as $key => $value)
        {
            $vv = $value;
            if(is_array($vv)){
                $vv=@join(',',$vv);
            }
            if(get_magic_quotes_gpc()) $vv = stripcslashes($vv);
            $cgi[$key] = trim($vv);
        }

        foreach($_GET as $key => $value)
        {
            $vv = $value;
            if(get_magic_quotes_gpc()) $vv = stripcslashes($vv);
            $cgi[$key] = trim($vv);
        }
        return $cgi;
    }
}