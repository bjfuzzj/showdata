<?php
/**
 * Created by PhpStorm.
 * User: bjfuzzj
 * Date: 16/9/17
 * Time: 00:57
 */
ini_set('display_errors', false);
ini_set('memory_limit', '100M');
set_time_limit(0);
ini_set("max_execution_time", 0);
require_once("plib/head.php");
require('config.php');
require('XLSXReader.php');
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');
function datainsert($sql)
{
    global $pub_mysql;
    $sql = getTrim($sql);
    $res = mysql_query($sql, $pub_mysql);
    return $res;
}

function getTrim($str)
{
    $str = trim($str);
    $str = trim($str, ',');
    return $str;
}
$cgi = getCGI();
upload_files();
if (false == empty($cgi['upexecl']))
{
    $file = TMP_PATH . "/" . $cgi['upexecl'];
    $Extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ('xls' == $Extension)
    {
        $Reader = new SpreadsheetReader($file);
        $sheetNames = $Reader->Sheets();
        foreach ($sheetNames as $Index => $sheetName)
        {
            $Reader->ChangeSheet($Index);
            $i = 0;
            foreach ($Reader as $key => $row) {
                if (empty($row[0]) || !$i) {
                    $i++;
                    continue;
                }
                $sql = 'insert into quxiang set ';
                foreach ($row as $k => $v) {
                    if (isset($insertArray[$k])) {
                        $v = getTrim($v);
                        $sqlkey = $insertArray[$k];
                        if (isset($changeArray[$sqlkey])) {
                            $v = array_search($v, $changeArray[$sqlkey]);
                        }
                        $sql .= "{$insertArray[$k]} = '" . $v . "', ";
                    }
                }
                if (false == datainsert($sql)) {
                    echo $sheetName, "中插入第{$i}条数据失败";
                } else {
                    echo $sheetName, "中插入第{$i}条数据成功";
                }
                echo "<br/>";
                $i++;
            }
        }

    }
    elseif ('xlsx' == $Extension)
    {
        $xlsx = new XLSXReader($file);
        $sheetNames = $xlsx->getSheetNames();
        foreach ($sheetNames as $sheetName)
        {
            $sheet = $xlsx->getSheet($sheetName);
            $data = $sheet->getData();
            array_shift($data);
            $i = 0;
            foreach ($data as $key => $row) {
                if (empty($row[0])) continue;
                $sql = 'insert into quxiang set ';
                foreach($row as $k=>$v)
                {
                    if (isset($insertArray[$k]))
                    {
                        $v = getTrim($v);
                        $sqlkey = $insertArray[$k];
                        if (isset($changeArray[$sqlkey]))
                        {
                            $v = array_search($v, $changeArray[$sqlkey]);
                        }
                        $sql .= "{$insertArray[$k]} = '".$v."', ";
                    }
                }
                if (false == datainsert($sql)) {
                    echo $sheetName, "中插入第{$i}条数据失败";
                } else {
                    echo $sheetName, "中插入第{$i}条数据成功";
                }
                echo "<br/>";
                $i++;
            }
        }
    }


    echo "更新成功";
    exit;

}
else
{

?>
<!DOCTYPE html>
<html>
<head>
    <title>数据管理后台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery UI
    <link href="https://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" rel="stylesheet" media="screen">
    -->
    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- styles -->
    <link href="css/styles.css" rel="stylesheet">
    <!--
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    -->
    <link href="vendors/form-helpers/css/bootstrap-formhelpers.min.css" rel="stylesheet">
    <link href="vendors/select/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendors/tags/css/bootstrap-tags.css" rel="stylesheet">

    <link href="css/forms.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <!-- Logo -->
                <div class="logo">
                    <h1><a href="index.html">数据管理后台</a></h1>
                </div>
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="input-group form">
                            <input type="text" class="form-control" placeholder="Search...">
	                       <span class="input-group-btn">
	                         <button class="btn btn-primary" type="button">Search</button>
	                       </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="navbar navbar-inverse" role="banner">
                    <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account <b class="caret"></b></a>
                                <ul class="dropdown-menu animated fadeInUp">
                                    <li><a href="profile.html">Profile</a></li>
                                    <li><a href="login.html">Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-md-2">
            <div class="sidebar content-box" style="display: block;">
                <?php include_once ('left.php'); ?>
            </div>
        </div>
        <div class="col-md-10">




            <div class="row">
                <div class="col-md-6">
                    <div class="content-box-large">
                        <div class="panel-heading">
                            <div class="panel-title">广电数据入口</div>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">


                                <fieldset>
                                    <legend>上传execl文件</legend>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">选择文件</label>
                                        <div class="col-md-10">
                                            <input type="file" class="btn btn-default" name="upexecl" id="exampleInputFile1">
                                            <p class="help-block">
                                                some help text here.
                                            </p>
                                        </div>
                                    </div>

                                </fieldset>


                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fa fa-save"></i>
                                                提交
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>


            </div>




            <!--  Page content -->
        </div>
    </div>
</div>

<footer>
    <div class="container">

        <div class="copy text-center">
            Copyright 2014 <a href='#'>Website</a>
        </div>

    </div>
</footer>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.js"></script>
<!-- jQuery UI -->
<script src="js/jquery-ui.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<script src="vendors/form-helpers/js/bootstrap-formhelpers.min.js"></script>

<script src="vendors/select/bootstrap-select.min.js"></script>

<script src="vendors/tags/js/bootstrap-tags.min.js"></script>

<script src="vendors/mask/jquery.maskedinput.min.js"></script>

<script src="vendors/moment/moment.min.js"></script>

<script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>

<!-- bootstrap-datetimepicker -->
<link href="vendors/bootstrap-datetimepicker/datetimepicker.css" rel="stylesheet">
<script src="vendors/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>

<!--
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
-->
<script src="js/custom.js"></script>
<script src="js/forms.js"></script>
</body>
</html>

<?php } ?>
