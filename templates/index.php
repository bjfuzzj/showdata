<?php
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

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script language=javascript>
		function checkForm_login(thisform)
		{
			if(thisform.admin.value == "")
			{
				alert('登录名不能为空');
				thisform.admin.focus();
				return false;
			}

			if(thisform.pwd.value == "")
			{
				alert('登录密码不能为空');
				thisform.pwd.focus();
				return false;
			}

			if(thisform.g_code.value == "")
			{
				alert('code不能为空');
				thisform.g_code.focus();
				return false;
			}
			return true;
		}

		function get_url(url)
		{
			var xmlhttp;
			var flag = 0;
			try{
				xmlhttp = new XMLHttpRequest();

			}catch(e){
				try{
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}catch(e) { }
			}

			var tm = new Date();
			cgi_prog = url + "&tm=" + tm.getTime();
			//alert(cgi_prog);
			xmlhttp.open("get", cgi_prog, false);
			xmlhttp.send(null);
			return  xmlhttp.responseText;
		}

		function checkForm_register(thisform)
		{
			if(thisform.admin.value == "")
			{
				alert('登录名不能为空');
				thisform.admin.focus();
				return false;
			}


			ret = get_url("checklogin.php?admin=" + thisform.admin.value);
			if(ret != "OK")
			{
				alert(ret);
				return false;
			}


			if(thisform.pwd.value == "")
			{
				alert('登录密码不能为空');
				thisform.pwd.focus();
				return false;
			}

			if(thisform.pwd.value != thisform.pwd1.value)
			{
				alert('登录密码和确认密码不一致');
				thisform.pwd1.focus();
				return false;
			}

			if(thisform.linkman.value == "")
			{
				alert('联系人不能为空');
				thisform.linkman.focus();
				return false;
			}

			if(thisform.phone.value == "")
			{
				alert('联系电话不能为空');
				thisform.phone.focus();
				return false;
			}
			return true;
		}
	</script>
</head>
<body class="login-bg">
<div class="header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<!-- Logo -->
				<div class="logo">
					<h1><a href="index.html">数据管理后台</a></h1>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="page-content container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-wrapper">
				<div class="box">
					<div class="content-wrap">
						<!--<form onsubmit="return checkForm_login(this);" method="post" name="myform" action="login.php"> -->
                        <form onsubmit="return checkForm_login(this);" method="post" name="myform" action="login.php?c=login&a=login">
						<h6>登录</h6>
						<input class="form-control" name="admin" type="text" placeholder="用户名">
						<input class="form-control" type="password" name="pwd" placeholder="密码">
						<input class="form-control" type="text" name="g_code" placeholder="U盾码">
						<div class="action">
							<input type="submit" class="btn btn-primary signup" value="登录"/>
							<!--<a class="btn btn-primary signup">登录</a> -->
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--<script src="https://code.jquery.com/jquery.js"></script> -->
<script src="js/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
