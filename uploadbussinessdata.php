<?php
/**
 * Created by PhpStorm.
 * User: bjfuzzj
 * Date: 16/9/17
 * Time: 00:57
 */
ini_set('memory_limit', '800M');
set_time_limit(0);
ini_set("max_execution_time", 0);
set_time_limit(0);
require_once("plib/head.php");
require('config.php');
require('XLSXReader.php');
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');
$pub_mysql = DBFaculty::getInstance();
function datainsert($sql)
{
    global $pub_mysql;
    $sql = getTrim($sql);
    $res = $pub_mysql->query($sql);
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
    $createDate = $cgi['today'];

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
                $sql = 'insert into bussinessdata set ';
                foreach ($row as $k => $v) {
                    if (isset($bussinessInsertArray[$k-1]) && $k > 0) {
                        $v = getTrim($v);
                        $sql .= "{$bussinessInsertArray[$k-1]} = '" . $v . "', ";
                    }
                }
                $sql .= " ctime = '".$createDate."'";
                echo $sql."<br/>";
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
                $sql = 'insert into bussinessdata set ';
                foreach($row as $k=>$v)
                {
                    if (isset($bussinessInsertArray[$k-1]) && $k>0 && $k <= count($bussinessInsertArray)+1)
                    {
                        $v = getTrim($v);
                        $sqlkey = $bussinessInsertArray[$k-1];
                        $sql .= "{$bussinessInsertArray[$k-1]} = '".$v."', ";
                    }
                }
                $sql .= " ctime = '".$createDate."'";
                echo $sql."<br/>";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>数据管理后台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>

    <script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />


    <link href="css/styles.css" rel="stylesheet">


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
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="navbar navbar-inverse" role="banner">
                    <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $ck_u_name; ?><b class="caret"></b></a>
                                <ul class="dropdown-menu animated fadeInUp">
                                    <li><a href="profile.html">Profile</a></li>
                                    <li><a href="logout.php">登出</a></li>
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
                <?php include_once("templates/left.php"); ?>
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
                                    <legend>请输入日期</legend>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">请输入日期</label>
                                        <div class="col-md-10">
                                            <input type="text" class="btn btn-default" name="today" id="today">
                                            <p class="help-block">
                                                some help text here.
                                            </p>
                                        </div>
                                    </div>

                                </fieldset>

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
        </div>
    </div>
</div>

<?php include_once("templates/footer.php"); ?>
<?php } ?>
