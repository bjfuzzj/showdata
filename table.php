<?php
ini_set('display_errors', true);
require_once("plib/head.php");
require('config.php');
$pub_mysql = DBFaculty::getInstance();
$data = array();
$where = " where 1=1 ";
$url = '?1=1';
$cgi = Tool::getCGI();
foreach($insertArray as $value)
{
	if (isset($cgi[$value]))
	{
		$where .= " and $value = '". $cgi[$value]. "' ";
		$url .= "&".$value."=".$cgi[$value];
	}

}

$page = isset($cgi['page']) ? intval($cgi['page']) : 1;
$page = $page<1?1:$page;
$pageSize = 20;
$limit = ($page-1)*$pageSize;

$sql = "select count(*) as total from quxiang $where ";
$res = $pub_mysql->query($sql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" . $pub_mysql->error);
$row = $res->fetch_array(MYSQLI_ASSOC);
$total = $row['total'];


$sql  = "select * from quxiang $where limit $limit,$pageSize";
$res = $pub_mysql->query($sql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" . $pub_mysql->error);
while($row = $res->fetch_array(MYSQLI_ASSOC))
{
	$row = changeRow($row);
	$data[] = $row;
}

function changeRow($row)
{
	global $changeArray;
	$keys = array_keys($changeArray);
	foreach($keys as $value)
	{
		if(isset($row[$value]))
		{
			$row[$value] = 	$changeArray[$value][$row[$value]];

		}
	}
	return $row;
}
$pageUrl = Page::getNavLink(Page::getPageNavTemplate($url."&page="),$page,$pageSize,$total);

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
  			<div class="content-box-large">
  				<div class="panel-heading">
					<div class="panel-title">数据展示</div>
				</div>
  				<div class="panel-body">
  					<div class="table-responsive">
						<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
			              <thead>
			                <tr>
			                  <th>分公司编号</th>
			                  <th>分公司编码</th>
			                  <th>部门</th>
			                  <th>客户类型</th>
								<th>盒号</th>
								<th>卡号</th>
								<th>终端类型</th>
								<th>交互方式</th>
								<th>清晰度</th>
								<th>产品名</th>
								<th>产品类型</th>
								<th>资费名称</th>
								<th>产品计费单位</th>
								<th>单价</th>
								<th>业务操作</th>
								<th>业务流水号</th>
								<th>金额</th>
								<th>缴费方式</th>
								<th>操作日期</th>
								<th>参考到期</th>
			                </tr>
			              </thead>
			              <tbody>
						  <?php foreach($data as $row) { ?>
			                <tr>
			                  <td><a   href="./table.php?companyno=<?=$row['companyno']?>"><?=$row['companyno']?></a> </td>
								<td><a   href="./table.php?companyname=<?=$row['companyname']?>"><?=$row['companyname']?></a></td>
								<td><a   href="./table.php?department=<?=$row['department']?>"><?=$row['department']?></a></td>
								<td><a   href="./table.php?customtype=<?=$row['customtype']?>"><?=$row['customtype']?></a></td>
								<td><a   href="./table.php?boxno=<?=$row['boxno']?>"><?=$row['boxno']?></a></td>
								<td><a   href="./table.php?cardno=<?=$row['cardno']?>"><?=$row['cardno']?></a></td>
								<td><a   href="./table.php?terminaltype=<?=$row['terminaltype']?>"><?=$row['terminaltype']?></a></td>
								<td><a   href="./table.php?interviewtype=<?=$row['interviewtype']?>"><?=$row['interviewtype']?></a></td>
								<td><a   href="./table.php?definition=<?=$row['definition']?>"><?=$row['definition']?></a></td>
								<td><a   href="./table.php?productname=<?=$row['productname']?>"><?=$row['productname']?></a></td>
								<td><a   href="./table.php?producttype=<?=$row['producttype']?>"><?=$row['producttype']?></a></td>
								<td><a   href="./table.php?feetype=<?=$row['feetype']?>"><?=$row['feetype']?></a></td>
								<td><a   href="./table.php?chargeunit=<?=$row['chargeunit']?>"><?=$row['chargeunit']?></a></td>
								<td><a   href="./table.php?priceper=<?=$row['priceper']?>"><?=$row['priceper']?></a></td>
								<td><a   href="./table.php?business=<?=$row['business']?>"><?=$row['business']?></a></td>
								<td><a   href="./table.php?businessno=<?=$row['businessno']?>"><?=$row['businessno']?></a></td>
								<td><a   href="./table.php?price=<?=$row['price']?>"><?=$row['price']?></a></td>
								<td><a   href="./table.php?paytype=<?=$row['paytype']?>"><?=$row['paytype']?></a></td>
								<td><a   href="./table.php?ctime=<?=$row['ctime']?>"><?=$row['ctime']?></a></td>
								<td><a   href="./table.php?expiredate=<?=$row['expiredate']?>"><?=$row['expiredate']?></a></td>
			                </tr>
						  <?php } ?>

			              </tbody>
			            </table>
                        <div>
                            <?=$pageUrl?>
                        </div>
  					</div>
  				</div>
  			</div>

		  </div>
		</div>
    </div>

<?php include_once("templates/footer.php"); ?>
