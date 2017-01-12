<?php
//ini_set('display_errors', true);
require_once("plib/head.php");
require_once("config.php");

$cgi = getCGI();
$startDate = !empty($cgi['startDate'])?$cgi['startDate']:'';
$endDate = !empty($cgi['endDate'])?$cgi['endDate']:'';
$orderSrc = !empty($cgi['orderSrc'])?$cgi['orderSrc']:'';
$status = !empty($cgi['status'])?$cgi['status']:'';

$where = " where 1=1 ";
if ($startDate) $where .= " and ctime>='".$startDate."' ";
if ($endDate) $where .= " and ctime<='".$endDate."' ";
if ($orderSrc) $where .= "and ordersrc='".$orderSrc."' ";
if ($status) $where .= "and status='".$status."' ";

$pub_mysql = DBFaculty::getInstance();

$sql = "select date_format(ctime ,'%Y-%m-%d') as date,count(*) as num from onlinedata {$where} group by date order by date asc";
$res = $pub_mysql->query($sql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" .$pub_mysql->error);
$days = array();
$nums = array();
$datas= array();
while($row = $res->fetch_array(MYSQLI_ASSOC))
{
	$days[] = $row['date'];
	$nums[] = $row['num'];
	$datas[] = $row;
}


$times = array();
$timeNums = array();
foreach ($dayArray as $key=>$value)
{
	$sql = "select '{$key}-{$value}' as time, count(*) as num from onlinedata {$where} AND DATE_FORMAT(ctime,'%H:%i:%s')>'{$key}' and DATE_FORMAT(ctime,'%H:%i:%s')<='{$value}'";
	$res = $pub_mysql->query($sql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" .$pub_mysql->error);
	while($row = $res->fetch_array(MYSQLI_ASSOC))
	{
		$times[] = $row['time'];
		$timeNums[] = $row['num'];
	}

}
//饼图
$priceArray = array();
$sql = "select priceper,count(*) as num from onlinedata {$where} group by priceper order by num desc limit 10";
$res = $pub_mysql->query($sql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" .$pub_mysql->error);
while($row = $res->fetch_array(MYSQLI_ASSOC))
{
    $priceArray[] = array('value'=> $row['num'], 'name'=>$row['priceper']);
}





$products = array();
$productNums = array();
$productShow = array();
$sql = "select productdesc,count(1) as num from onlinedata {$where} and  productdesc!='' group by productdesc  order by num desc limit 30";
$res = $pub_mysql->query($sql) or sys_exit("系统忙， 请稍候再试。", $sql . ":\n" . $pub_mysql->error);
while($row = $res->fetch_array(MYSQLI_ASSOC))
{
	$products[] = $row['productdesc'];
	$productNums[] = $row['num'];
    $productShow[] = $row;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>数据管理后台</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
	<script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
	<script type="text/javascript" src="/bower_components/moment/locale/zh-cn.js"></script>
	<script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />

	<link href="css/styles.css" rel="stylesheet">
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
			<form action="" method="get">
				<div class="col-md-3">

					<div class="form-group">
						<div class="input-group date">
							<input size="16" placeholder="选择开始时间" name="startDate" type="text" value="<?=$startDate?$startDate:''?>" class="start_date form-control">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
						</div>
					</div>
				</div>
				<div class="col-md-3">

					<div class="form-group">
						<div class="input-group date">
							<input size="16" placeholder="选择结束时间" name="endDate" type="text" value="<?=$endDate?$endDate:''?>" class="start_date form-control">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
						</div>
					</div>
				</div>

				<div class="col-md-2">
					<select name="orderSrc" class="form-control">
						<option value="0"  <?=!$orderSrc?"selected":'' ?>>选择来源</option>
						<option value="优酷TV" <?=$orderSrc=='优酷TV'?'selected="selected"':''?> >优酷TV</option>
                        <option value="优酷TV优惠包" <?=$orderSrc=='优酷TV优惠包'?'selected="selected"':''?> >优酷TV优惠包</option>
                        <option value="芒果TV" <?=$orderSrc=='芒果TV'?'selected="selected"':''?> >芒果TV</option>
                        <option value="芒果TV线上包" <?=$orderSrc=='芒果TV'?'selected="selected"':''?> >芒果TV线上包</option>
						<option value="杭州华数" <?=$orderSrc=='杭州华数'?'selected="selected"':''?> >杭州华数</option>
						<option value="搜狐视频" <?=$orderSrc=='搜狐视频'?'selected="selected"':''?> >搜狐视频</option>
					</select>
				</div>


                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="0"  <?=!$status?"selected":'' ?>>订单状态</option>
                        <option value="订购成功" <?=$status=='订购成功'?'selected="selected"':''?> >订购成功</option>
                        <option value="待付款" <?=$status=='待付款'?'selected="selected"':''?> >待付款</option>
                        <option value="订购失败" <?=$status=='订购失败'?'selected="selected"':''?> >订购失败</option>
                    </select>
                </div>


				<div class="col-md-2">
					<input type="submit" name="submit" class="btn"  value="查询">
				</div>

				<form>
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

            <div class="row">

                <div class="col-md-6">
                    <div class="content-box-large">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>片名</th>
                                        <th>订购数</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($productShow as $row) { ?>
                                        <tr>
                                            <td><a> <?=$row['productdesc']?></a> </td>
                                            <td><a><?=$row['num']?></a></td>
                                        </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div id="bingtu" style="width: 800px;height:400px;"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('bingtu'));
                        option = {
                            backgroundColor: '#2c343c',

                            title: {
                                text: '金额饼图',
                                left: 'center',
                                top: 20,
                                textStyle: {
                                    color: '#ccc'
                                }
                            },

                            tooltip : {
                                trigger: 'item',
                                formatter: "{a} <br/>{b} : {c} ({d}%)"
                            },

                            visualMap: {
                                show: false,
                                min: 80,
                                max: 600,
                                inRange: {
                                    colorLightness: [0, 1]
                                }
                            },
                            series : [
                                {
                                    name:'访问来源',
                                    type:'pie',
                                    radius : '55%',
                                    center: ['50%', '50%'],
                                    data:<?=json_encode($priceArray);?>.sort(function (a, b) { return a.value - b.value}),
                                    roseType: 'angle',
                                    label: {
                                        normal: {
                                            textStyle: {
                                                color: 'rgba(255, 255, 255, 0.3)'
                                            }
                                        }
                                    },
                                    labelLine: {
                                        normal: {
                                            lineStyle: {
                                                color: 'rgba(255, 255, 255, 0.3)'
                                            },
                                            smooth: 0.2,
                                            length: 10,
                                            length2: 20
                                        }
                                    },
                                    itemStyle: {
                                        normal: {
                                            color: '#c23531',
                                            shadowBlur: 200,
                                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                                        }
                                    }
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
                    <div class="content-box-large">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>金额</th>
                                        <th>订单数</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($priceArray as $row) { ?>
                                        <tr>
                                            <td><a> <?=$row['name']?></a> </td>
                                            <td><a><?=$row['value']?></a></td>
                                        </tr>
                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
		$('.start_date').datetimepicker({format: 'YYYY-MM-DD HH:mm',locale:'zh-cn'});
		$(".end_date").datetimepicker({format: 'yyyy-mm-dd hh:ii',locale:'zh-cn'});
	});


</script>

<?php include_once("templates/footer.php"); ?>
