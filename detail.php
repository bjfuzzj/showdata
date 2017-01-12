<?php
require_once("plib/head.php");
$cgi = getCGI();
$startDate = !empty($cgi['startDate'])?$cgi['startDate']:'';
$endDate = !empty($cgi['endDate'])?$cgi['endDate']:'';


$where = " where 1=1 ";
if ($startDate) $where .= " and ctime>='".$startDate." '";
if ($endDate) $where .= " and ctime<='".$endDate." '";



$sql = "select date_format(ctime ,'%Y-%m-%d') as date,count(*) as num from onlinedata {$where} group by date order by date asc";
$res = mysql_query($sql, $pub_mysql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" . mysql_error());
$days = array();
$nums = array();
$datas= array();
while($row = mysql_fetch_array($res, MYSQL_ASSOC))
{
	$days[] = $row['date'];
	$nums[] = $row['num'];
	$datas[] = $row;
}


$times = array();
$timeNums = array();
foreach ($dayArray as $key=>$value)
{
	$sql = "select '{$key}-{$value}' as time, count(*) as num from onlinedata  WHERE  DATE_FORMAT(ctime,'%H:%i:%s')>'{$key}' and DATE_FORMAT(ctime,'%H:%i:%s')<='{$value}'";
	$res = mysql_query($sql, $pub_mysql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" . mysql_error());
	while($row = mysql_fetch_array($res, MYSQL_ASSOC))
	{
		$times[] = $row['time'];
		$timeNums[] = $row['num'];
	}

}





$products = array();
$productNums = array();
$sql = "select productdesc,count(1) as num from onlinedata where productdesc!='' group by productdesc  order by num desc limit 30";
$res = mysql_query($sql, $pub_mysql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" . mysql_error());
while($row = mysql_fetch_array($res, MYSQL_ASSOC))
{
	$products[] = $row['productdesc'];
	$productNums[] = $row['num'];
}



?>






<!DOCTYPE html>
<html>
<head>
	<title>数据管理后台</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- styles -->
	<link href="css/styles.css" rel="stylesheet">
	<link href="http://www.bootcss.com/p/bootstrap-datetimepicker/bootstrap-datetimepicker/css/datetimepicker.css" ref="stylesheet">
	<script src="http://www.bootcss.com/p/bootstrap-datetimepicker/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script src="//cdn.bootcss.com/echarts/3.2.3/echarts.common.min.js"></script>


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
					<h1><a href="projlist.php">数据管理后台</a></h1>
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
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $ck_u_name; ?> <b class="caret"></b></a>
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
			<form action="" method="get">
			<div class="col-md-4">
				<h4>开始日期</h4>
				<input size="16" name="startDate" type="text" value="" readonly class="start_date">

				<script type="text/javascript">
					$(".start_datetime").datetimepicker({format: 'yyyy-mm-dd'});
				</script>
			</div>
			<div class="col-md-4">
				<h4>结束日期</h4>
				<input size="16" name="endDate" type="text" value="" readonly class="end_date">

				<script type="text/javascript">
					$(".end_datetime").datetimepicker({format: 'yyyy-mm-dd'});
				</script>
			</div>
			<form>
			<div class="col-md-2">
				<h4>Date Picker</h4>
				<p>
				<div class="bfh-datepicker" data-format="y-m-d" data-date="today"></div>
				</p>
			</div>
			<div class="row">
				<div class="col-md-10">
					<div id="dataperday" style="width: 800px;height:400px;"></div>
					<script type="text/javascript">
						// 基于准备好的dom，初始化echarts实例
						var myChart = echarts.init(document.getElementById('dataperday'));
						option = {
							title: {
								text: '订购数据(按天)'
							},
							tooltip: {
								trigger: 'axis'
							},
							legend: {
								data:['订购数据(按天)']
							},
							grid: {
								left: '3%',
								right: '4%',
								bottom: '3%',
								containLabel: true
							},
							toolbox: {
								feature: {
									saveAsImage: {}
								}
							},
							xAxis: {
								type: 'category',
								boundaryGap: false,
								//data: ['周一','周二','周三','周四','周五','周六','周日']
								data: <?=json_encode($days);?>
							},
							yAxis: {
								type: 'value'
							},
							series: [
								{
									name:'订购数据(按天)',
									type:'line',
									stack: '总量',
									data:<?=json_encode($nums)?>
								}
							]
						};

						// 使用刚指定的配置项和数据显示图表。
						myChart.setOption(option);
					</script>

				</div>

			</div>


			<div class="row">
				<div class="col-md-6">
					<div id="datapertime" style="width: 800px;height:400px;"></div>
					<script type="text/javascript">
						// 基于准备好的dom，初始化echarts实例
						var myChart = echarts.init(document.getElementById('datapertime'));
						option = {
							title: {
								text: '订购数据(按时间段)'
							},
							tooltip: {
								trigger: 'axis'
							},
							legend: {
								data:['订购数据(按时间段)']
							},
							grid: {
								left: '3%',
								right: '4%',
								bottom: '3%',
								containLabel: true
							},
							toolbox: {
								feature: {
									saveAsImage: {}
								}
							},
							xAxis: {
								type: 'category',
								boundaryGap: false,
								//data: ['周一','周二','周三','周四','周五','周六','周日']
								data: <?=json_encode($times);?>
							},
							yAxis: {
								type: 'value'
							},
							series: [
								{
									name:'订购数据(按时间段)',
									type:'line',
									stack: '总量',
									data:<?=json_encode($timeNums)?>
								}
							]
						};

						// 使用刚指定的配置项和数据显示图表。
						myChart.setOption(option);
					</script>

				</div>

			</div>

			<div class="row">
				<div class="col-md-6">
					<div id="productnum" style="width: 800px;height:400px;"></div>
					<script type="text/javascript">
						// 基于准备好的dom，初始化echarts实例
						var productnum = echarts.init(document.getElementById('productnum'));

						option = {
							title: {
								text: '订购排行榜(前30)'
							},
							color: ['#3398DB'],
							tooltip : {
								trigger: 'axis',
								axisPointer : {            // 坐标轴指示器，坐标轴触发有效
									type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
								}
							},
							grid: {
								left: '3%',
								right: '4%',
								bottom: '3%',
								containLabel: true
							},
							xAxis : [
								{
									type : 'category',
									data : <?=json_encode($products)?>,
									axisTick: {
										alignWithLabel: true
									}
								}
							],
							yAxis : [
								{
									type : 'value'
								}
							],
							series : [
								{
									name:'订购量',
									type:'bar',
									barWidth: '60%',
									data:<?=json_encode($productNums)?>
								}
							]
						};


						// 使用刚指定的配置项和数据显示图表。
						productnum.setOption(option);
						productnum.on('click', function (params) {
							window.open('https://www.baidu.com/s?wd=' + encodeURIComponent(params.name));
						});
					</script>

				</div>

			</div>

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
<script src="js/custom.js"></script>
</body>
</html>